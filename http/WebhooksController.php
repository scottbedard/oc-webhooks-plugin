<?php namespace Bedard\Webhooks\Http;

use Bedard\Webhooks\Models\Hook;
use Illuminate\Routing\Controller;

class WebhooksController extends Controller
{

    /**
     * Execute a webhook
     *
     * @return \RainLab\Blog\Models\Post
     */
    public function execute($token)
    {
        $hook = Hook::whereToken($token)->first();
        dd ($hook);
    }
}
