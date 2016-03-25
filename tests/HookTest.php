<?php namespace Bedard\Shop\Tests\Models;

use Exception;
use Carbon\Carbon;
use PluginTestCase;
use Bedard\Webhooks\Models\Log;
use Bedard\Webhooks\Models\Hook;

class HookTest extends PluginTestCase
{

    protected function newHook($data = [])
    {
        $hook = new Hook;
        $hook->name = "Dummy";
        $hook->is_enabled = true;
        $hook->http_method = "get";

        foreach ($data as $property => $value) {
            $hook->$property = $value;
        }

        $hook->save();
        return $hook;
    }

    public function test_hooks_are_created_with_a_random_token()
    {
        $hook = $this->newHook();
        $this->assertTrue((bool) $hook->token);
    }

    public function test_executing_a_script_and_logging_the_output()
    {
        $hook = $this->newHook(['script' => 'echo 12345']);
        $this->assertNull($hook->executed_at);
        $hook->executeScript();

        $log = Log::whereHookId($hook->id)->first();
        $this->assertEquals(12345, $log->output);
    }

    public function test_joining_the_log_count_to_hooks()
    {
        $dummy1 = $this->newHook();
        $dummy2 = $this->newHook();
        $dummy2->executeScript();

        $this->assertEquals(0, Hook::joinLogsCount()->find($dummy1->id)->logsCount);
        $this->assertEquals(1, Hook::joinLogsCount()->find($dummy2->id)->logsCount);
    }

    public function test_http_accessors()
    {
        $hook = $this->newHook(['http_method' => 'GET']);
        $this->assertEquals('GET', $hook->httpMethod);
        $this->assertTrue(preg_match('/(.*)\/bedard\/webhooks\/(\w{40})/', $hook->url) === 1);
    }

    public function test_only_enabled_hooks_can_be_executed()
    {
        $hook = $this->newHook(['is_enabled' => true]);
        $hook->executeScript();

        $hook->is_enabled = false;
        $this->setExpectedException('Bedard\Webhooks\Exceptions\ScriptDisabledException');
        $hook->executeScript();
    }

    public function test_finding_a_hook_by_token_and_method()
    {
        $hook = $this->newHook(['http_method' => 'get']);
        $this->assertEquals($hook->id, Hook::findByTokenAndMethod($hook->token, 'get')->id);
    }

    public function test_hooks_can_be_enabled_and_disabled_via_scopes()
    {
        $hook = $this->newHook(['is_enabled' => true]);

        Hook::whereId($hook->id)->disable();
        $this->assertEquals(false, Hook::find($hook->id)->is_enabled);

        Hook::whereId($hook->id)->enable();
        $this->assertEquals(true, Hook::find($hook->id)->is_enabled);
    }

    public function test_queueing_a_script_for_execution()
    {
        $hook = $this->newHook(['script' => 'echo hello']);
        $hook->queueScript();
        $this->assertEquals(1, Log::whereHookId($hook->id)->count());
    }

    public function test_find_and_execute_script_scope()
    {
        $hook = $this->newHook(['script' => 'echo hello']);
        Hook::findAndExecuteScript($hook->id);
        $this->assertEquals(1, Log::whereHookId($hook->id)->count());
    }
}
