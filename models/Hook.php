<?php namespace Bedard\Webhooks\Models;

use Model;

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

}
