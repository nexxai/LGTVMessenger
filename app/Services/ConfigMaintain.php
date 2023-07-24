<?php

namespace App\Services;

class ConfigMaintain
{
    public string $config_base_path;

    public function __construct()
    {
        $this->config_base_path = base_path().'/config/lgtvs.php';
    }

    public function create_lgtvs_file(): void
    {
        $empty_config_file = <<< 'PHP'
        <?php

        return [
        ];
        PHP;

        file_put_contents($this->config_base_path, $empty_config_file);
    }

    public function add($instance)
    {
        $config = include $this->config_base_path;

        $config[] = $instance;
        $newConfig = "<?php\n\nreturn ".$this->better_var_export($config).';';
        file_put_contents($this->config_base_path, $newConfig);
    }

    protected function better_var_export($expression)
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
