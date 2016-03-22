<?php namespace Bedard\Webhooks\Models;

use Model;
use Carbon\Carbon;

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
    protected $fillable = [];

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
     * Touch the executed_at timestamp
     *
     * @return boolean
     */
    public function execute()
    {
        $this->executed_at = Carbon::now();
        chdir($this->directory);
        $output = `$this->script`;
        return $this->save();
    }

}
