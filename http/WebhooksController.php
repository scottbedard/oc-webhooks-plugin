<?php namespace Bedard\Webhooks\Http;

use Request;
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
        // Find the webhook
        $hook = Hook::whereToken($token)->whereHttpMethod(Request::method())->first();

        if (!$hook) {
            return abort(404, e(trans('bedard.webhooks::lang.responses.not_found')));
        }

        // Execute the script
        $result = $hook->execute();

        if (!$result) {
            return abort(500, e(trans('bedard.webhooks::lang.responses.failed')));
        }

        // Return a 200 response
        return Response::make(e(trans('bedard.webhooks::lang.responses.success')), 200);
    }
}
