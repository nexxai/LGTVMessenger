<?php

namespace App\Services;

class ModifyConfig
{
    public function __construct(public string $base_path, public bool $overwrite = false)
    {
    }

    public function create_blank_file(): void
    {
        if (file_exists($this->base_path) && !$this->overwrite) {
            return;
        }

        $empty_file = <<< 'PHP'
        <?php

        return [
        ];
        PHP;

        file_put_contents($this->base_path, $empty_file);
    }

    public function add($instance)
    {
        if (!file_exists($this->base_path)) {
            $this->create_blank_file();
        }

        $contents = $this->read();

        // Don't add a duplicate; just return without doing anything
        if (in_array($instance, $contents)) {
            return;
        }

        $contents[] = $instance;
        $new_file = "<?php\n\nreturn " . $this->better_var_export($contents) . ';';
        file_put_contents($this->base_path, $new_file);
    }

    public function remove(string $index)
    {
        $contents = $this->read();

        unset($contents[$index]);

        $file = "<?php\n\nreturn " . $this->better_var_export($contents) . ';';
        file_put_contents($this->base_path, $file);
    }

    public function read()
    {
        return include $this->base_path;
    }

    protected function better_var_export($expression)
    {
        $export = var_export($expression, true);
        $export = preg_replace('/^([ ]*)(.*)/m', '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = implode(PHP_EOL, array_filter(['['] + $array));

        return $export;
    }
}
