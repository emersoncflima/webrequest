<?php

namespace EasyRoute;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION | \Attribute::TARGET_CLASS)]
class Path {
	private $path;
    private $methods;

    public function __construct(string $path, string | array $method = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD']) {
        $this->path = $path;
        $this->methods = is_string($method) ? [$method] : $method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getMethods() {
        return $this->methods;
    }

    public static function discoverDefinedFunctions() {
        $r = [];
        foreach (get_defined_functions(true)["user"] as $f) {
            $rf = new \ReflectionFunction($f);
            $attrs = $rf->getAttributes();
            foreach ($attrs as $attr) {
                if ($attr->getName() == Path::class) {
                    $r[$f] = $attr;
                }
            }
        }
        return $r;
    }

    public static function dump() {
        echo '<h2>Routes</h2>';
        foreach (Path::discoverDefinedFunctions() as $h => $ra) {
            $attr = $ra->newInstance(); 
            $p = $attr->getPath();
            echo "<table>";
            foreach($attr->getMethods() as $m) {
                echo "<tr><td>$m</td><td>$p</td><td>$h</td></tr>\n";
            }
            echo "</table>";
        }
    }

}