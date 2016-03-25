<?php namespace Bedard\Webhooks\Models;

use DB;
use Model;
use Queue;
use Backend;
use Carbon\Carbon;
use Bedard\Webhooks\Models\Log;
use Bedard\Webhooks\Exceptions\ScriptDisabledException;

/**
 * Hook Model
 */
class Hook extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_webhooks_hooks';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'script',
        'http_method',
        'is_enabled',
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * @var array Datetime fields
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'executed_at',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'logs' => [
            'Bedard\Webhooks\Models\Log',
        ],
    ];

    /**
     * Generate a unique token
     *
     * @return void
     */
    public function beforeCreate()
    {
        do {
            $this->token = str_random(40);
        } while (self::where('token', $this->token)->exists());
    }

    /**
     * Execute the script and log the output
     *
     * @return boolean
     */
    public function queueScript()
    {
        $id = $this->id;
        Queue::push(function($job) use ($id) {
            $hook = Hook::findOrFail($id)->executeScript();
        });
    }

    /**
     * Execute the shell script and log the output
     *
     * @return string
     */
    public function executeScript()
    {
        // Make sure the script is enabled
        if (!$this->is_enabled) {
            throw new ScriptDisabledException();
        }

        // Run the script and log the output
        $output = shell_exec($this->script);
        Log::create(['hook_id' => $this->id, 'output' => $output]);

        // Update our executed_at timestamp
        $this->executed_at = Carbon::now();
        $this->save();
    }

    /**
     * Returns the script with normalized line endings
     *
     * @return void
     */
    public function getScriptAttribute($script)
    {
        return preg_replace('/\r\n?/', PHP_EOL, $script);
    }

    /**
     * Find a hook by token and HTTP method
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  string                           $token
     * @param  $httpMethod                      $httpMethod
     * @return \October\Rain\Database\Builder
     */
    public function scopeFindByTokenAndMethod($query, $token, $httpMethod) {
        return $query->whereIsEnabled(true)
            ->whereHttpMethod($httpMethod)
            ->whereToken($token)
            ->firstOrFail();
    }

    /**
     * Enables or disables webhooks
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return integer
     */
    public function scopeSetIsEnabled($query, $isEnabled)
    {
        return $query->update([
            'is_enabled' => $isEnabled,
            'updated_at' => Carbon::now(),
        ]);
    }

    public function scopeDisable($query)
    {
        return $query->setIsEnabled(false);
    }

    public function scopeEnable($query)
    {
        return $query->setIsEnabled(true);
    }

    /**
     * Left joins the logs count
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeJoinLogsCount($query)
    {
        $subquery = Log::select(DB::raw('id, hook_id, COUNT(*) as logs_count'))
            ->groupBy('hook_id')
            ->getQuery()
            ->toSql();

        return $query
            ->addSelect('bedard_webhooks_hooks.*')
            ->addSelect('logs.logs_count')
            ->leftJoin(DB::raw('(' . $subquery . ') logs'), 'bedard_webhooks_hooks.id', '=', 'logs.hook_id');
    }

    /**
     * Helper for snake_case http method
     *
     * @return string
     */
    public function getHttpMethodAttribute()
    {
        return array_key_exists('http_method', $this->attributes)
            ? $this->attributes['http_method']
            : 'post';
    }

    /**
     * Count the number of logs this hook has
     *
     * @return integer
     */
    public function getLogsCountAttribute($logs)
    {
        return array_key_exists('logs_count', $this->attributes)
            ? (int) $this->attributes['logs_count']
            : 0;
    }

    /**
     * Returns a url to this webhook
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('bedard/webhooks', [ 'token' => $this->token ]);
    }
}
