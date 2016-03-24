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
     * @var array Datetime fields
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'executed_at',
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
     * @var array Relations
     */
    public $hasMany = [
        'logs' => [
            'Bedard\Webhooks\Models\Log',
        ],
    ];

    /**
     * Execute the script and log the output
     *
     * @return boolean
     */
    public function execute()
    {
        if (!$this->is_enabled) {
            throw new ScriptDisabledException();
        }

        $id = $this->id;
        Queue::push(function($job) use ($id) {
            $hook = Hook::find($id);
            $output = shell_exec($hook->singleLineScript);
            $hook->logOutput($output);
            $hook->touchExecutedAt();
        });
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
    public function getLogsCountAttribute()
    {
        return array_key_exists('logs_count', $this->attributes)
            ? (int) $this->attributes['logs_count']
            : 0;
    }

    /**
     * Returns the shell script as a single line
     *
     * @return string
     */
    public function getSingleLineScriptAttribute()
    {
        $delimeter = ' && ';
        return implode($delimeter, explode(PHP_EOL, $this->script));
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

    /**
     * Log some output
     *
     * @param  string   $output
     * @return \Bedard\Webhooks\Models\Log
     */
    public function logOutput($output)
    {
        return Log::create([
            'hook_id' => $this->id,
            'output' => $output,
        ]);
    }

    /**
     * Touch the model's executed_at timestamp
     *
     * @return boolean
     */
    public function touchExecutedAt()
    {
        $this->executed_at = Carbon::now();
        return $this->save();
    }
}
