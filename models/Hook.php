<?php namespace Bedard\Webhooks\Models;

use DB;
use Model;
use Backend;
use Carbon\Carbon;
use Bedard\Webhooks\Models\Log;

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
        'directory',
        'script',
        'http_method',
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
        // Execute the hook
        if (!empty($this->directory)) {
            chdir($this->directory);
        }

        $output = `$this->script`;

        // Log the output
        Log::create([
            'hook_id' => $this->id,
            'output' => $output,
        ]);

        // Return the results
        $this->executed_at = Carbon::now();
        return $this->save();
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
        return $this->attributes['http_method'];
    }

    /**
     * Count the number of logs this hook has
     *
     * @return integer
     */
    public function getLogsCountAttribute()
    {
        return (int) $this->attributes['logs_count'];
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
