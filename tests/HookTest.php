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
        $hook->execute();

        $log = Log::whereHookId($hook->id)->first();
        $this->assertEquals(12345, $log->output);
    }

    public function test_joining_the_log_count_to_hooks()
    {
        $dummy1 = $this->newHook();
        $dummy2 = $this->newHook();
        $dummy2->execute();

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
        $hook->execute();

        $hook->is_enabled = false;
        $this->setExpectedException('Bedard\Webhooks\Exceptions\ScriptDisabledException');
        $hook->execute();
    }

    public function test_multiline_scripts_are_compressed_to_a_single_line()
    {
        $hook1 = $this->newHook(['script' =>
"echo hello
echo world"
        ]);

        $hook2 = $this->newHook(['script' =>
"echo foo

echo bar"
        ]);

        $this->assertEquals('echo hello && echo world', $hook1->singleLineScript);
        $this->assertEquals('echo foo && echo bar', $hook2->singleLineScript);
    }
}
