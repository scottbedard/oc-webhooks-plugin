<?php

Route::any('bedard/webhooks/{token}', 'Bedard\Webhooks\Http\WebhooksController@execute');
