<?php

class render_content extends render_base
{
    public static function init()
    {
        xml_serve::add_handler("content", get_class() . "::render");
    }

    public static function render($el)
    {
        php_logger::log("CALL");
        $id = $el->getAttribute("id");
        $src = $el->getAttribute("src");
        $type = $el->getAttribute("type");

        if ($src == "") $src = xml_serve::$template->get("/*/content[@id='$id']/@src");
        if ($src == "") $src = "$id.html";

        if ($type == "") $type = xml_serve::$template->get("/*/content[@id='$id']/@type");
        if ($type == "") $type = substr($src, strrpos($src, '.') + 1);
        php_logger::debug("id=$id", "src=$src", "type=$type");

        $res = xml_serve::resource_resolver()->resolve_file($src, "template", xml_serve::template_name());
        php_logger::log("template_name", xml_serve::template_name(), "res=$res");

        if ($res == "") return xml_serve::empty_content();
        $cont = file_get_contents($res);

        switch (strtolower($type)) {
            case 'text':
            case 'txt':
                return xml_serve::xml_content($cont);
            case 'xml':
            case 'xhtml':
                return xml_serve::xml_content($res);
            case 'html':
                $config = array(
                    'indent'         => true,
                    'output-xml'     => true,
                    'input-xml'     => true,
                    'wrap'         => '1000');
                $tidy = new tidy();
                $tidy->parseString($cont, $config, 'utf8');
                $tidy->cleanRepair();
                $cont = "<span>" . tidy_get_output($tidy) . "</span>";
                $result = xml_serve::xml_content($cont);
                return $result;
            case 'md':
                require_once(__DIR__ . "support/slimdown.php");
                $html = Slimdown::render($cont);
                $xml = xml_file::make_tidy_string($html, "xhtml");
                return xml_serve::xml_content(Slimdown::render($xml));
            default:
                return xml_serve::xml_content("<span>!<[CDATA[".str_replace(">", "&gt;", $cont)."]]></span>");
        }

        return $el;
    }
}
