<?php

namespace App\Services;

class ConfigMaintain
{
    public function add($instance, $env_line)
    {
        $config_path = base_path().'/config/lgtvs.php';
        $env_path = base_path().'/.env';

        $config = include $config_path;
        $env = file_get_contents($env_path);

        $config[] = $instance;
        $newConfig = "<?php\n\nreturn ".$this->varexport($config).';';
        file_put_contents($config_path, $newConfig);

        $env = $env."\n".$env_line;
        file_put_contents($env_path, $env);
    }

    protected function varexport($expression)
    {
        $export = var_export($expression, true);
        $export = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $array = preg_replace('/\'env\(([A-Z_]*)\)\'/', 'env(\'$1\')', $array);
        $export = implode(PHP_EOL, array_filter(['['] + $array));

        return $export;
    }
}
