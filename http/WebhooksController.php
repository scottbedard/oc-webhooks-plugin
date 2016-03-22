<?php namespace Bedard\Webhooks\Http;

use Response;
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
        if ($hook = Hook::whereToken($token)->first()) {
            return $hook->execute()
                ? Response::make(e(trans('bedard.webhooks::lang.responses.ok')), 200)
                : Response::make(e(trans('bedard.webhooks::lang.responses.failed')), 500);
        }

        return Response::make(e(trans('bedard.webhooks::lang.responses.not_found')), 404);
    }
}
