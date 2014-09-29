<?php
namespace Nayjest\DbDiff\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Nayjest\DbDiff\Operation;
use Nayjest\DbDiff\Diff;

class DbDiffMakeCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db-diff:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $table1 = $this->option('table1');
        $table2 = $this->option('table2');
        $fields = $this->option('fields');
        if ($fields) {
            $fields = explode(',', $fields);
        } else {
            $fields = '*';
        }


        $op = new Operation(compact('table1', 'table2', 'fields'));
        $op->save();
        $op->getDiffProcessor()->intoTempTable($op->id);
        $this->info("Diff #$op->id ready.");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['table1', 'a', InputOption::VALUE_REQUIRED, 'Table 1.', null],
            ['table2', 'b', InputOption::VALUE_REQUIRED, 'Table 2.', null],
            ['fields', 'f', InputOption::VALUE_OPTIONAL, 'Fields.', null],
        ];
    }

}
