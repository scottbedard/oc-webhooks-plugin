<?php namespace Bedard\Webhooks\Http;

use Request;
use Response;
use Exception;
use Bedard\Webhooks\Models\Hook;
use Illuminate\Routing\Controller;

class WebhooksController extends Controller
{

    /**
     * Execute a webhook
     *
     * @param  string   $token
     * @return Response
     */
    public function execute($token)
    {
        try {
            // If no webhook was found, return a 404
            if (!$hook = Hook::findByTokenAndMethod($token, Request::method())) {
                return Response::make(e(trans('bedard.webhooks::lang.responses.not_found')), 404);
            }

            // Otherwise queue the script for execution, and return a 200
            $hook->queueScript();
            return Response::make(e(trans('bedard.webhooks::lang.responses.success')), 200);
        } catch (Exception $e) {
            return Response::make(e(trans('bedard.webhooks::lang.responses.failed')), 500);
        }
    }
}
