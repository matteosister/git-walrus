<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.22
 * Just for fun...
 */


namespace CypressLab\GitWalrus\Tests;

use CypressLab\GitWalrus\Application;
use GitElephant\Command\Caller\Caller;
use GitElephant\GitBinary;
use GitElephant\Repository;
use Silex\WebTestCase as SilexWebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\HttpKernel;

class WebTestCase extends SilexWebTestCase
{
    /**
     * repository path
     *
     * @var string
     */
    protected $path;

    /**
     * gitelephant repository instance
     *
     * @var Repository
     */
    private $repo;

    /**
     * @param Client $client
     */
    protected function isJsonResponse(Client $client)
    {
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));
    }

    protected function countItems(Client $client, $expected, $key = null)
    {
        $jsonContent = json_decode($client->getResponse()->getContent(), true);
        $test = $jsonContent;
        if (null !== $key) {
            $test = $jsonContent[$key];
        }
        $this->assertCount(
            $expected,
            $test,
            "response:\n". $client->getResponse()->getContent()
        );
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $this->createRepository();
        $repositoryRoot = $this->path;
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function createRepository()
    {
        $tempDir = realpath(sys_get_temp_dir());
        $tempName = tempnam($tempDir, 'gitelephant');
        $this->path = $tempName;
        @unlink($this->path);
        $fs = new Filesystem();
        $fs->mkdir($this->path);
        $this->repo = Repository::open($this->path);
        $this->repo->init();
    }

    /**
     * add a file to the repo
     *
     * @param        $path
     * @param string $content
     */
    public function addFile($path, $content = null)
    {
        $fs = new Filesystem();
        $fs->dumpFile($this->path.'/'.$path, $content ?: 'test content');
    }

    /**
     * commit all
     */
    public function commit($msg = null)
    {
        $this->repo->commit($msg ?: 'commit automatic test message', true);
    }

    /**
     * @param $name
     */
    public function createBranch($name)
    {
        $this->repo->createBranch($name);
    }

    /**
     * @param $name
     */
    public function checkout($name)
    {
        $this->repo->checkout($name);
    }

    /**
     * @return Repository
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * phpunit tearDown
     */
    public function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->path);
        $this->path = null;
        $this->repo = null;
    }
}
