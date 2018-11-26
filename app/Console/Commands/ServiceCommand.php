<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--trait}? {--t}?';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service command';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Services';


    /**
     * Create a new command instance.
     *
     * @return void
     */

//     public function __construct()
//     {
//         parent::__construct();
//     }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
//     public function handle()
//     {
// //        $name = $this->parseName($this->getNameInput());
//         $name = $this->getNameInput();

//         $path = $this->getPath($this->type.'/'.$name);

//         if ($this->alreadyExists($this->getNameInput())) {
//             $this->error($this->type.' already exists!');

//             return false;
//         }

//         $this->makeDirectory($path);

//         $this->files->put($path, $this->buildClass($name));

//         $this->info($this->type.' created successfully.');
//     }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
       if ($this->option('trait') || $this->option('t')) {
            return __DIR__.'/../stubs/service.trait.stub';
        }
        return __DIR__.'/../stubs/service.stub';
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
        return $rootNamespace;
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['trait', 't', InputOption::VALUE_NONE, 'Generate a trait class.'],
        ];
    }



}
