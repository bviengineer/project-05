<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* home.php */
class __TwigTemplate_64677209807a1791ad7a069894f01bde23371a06ef0aa1af2edbd9921d4318ab extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">
        <title>SparkBlog</title>
        <link href='https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Noto+Sans:700,400' rel='stylesheet' type='text/css'>
        <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">
        <link rel=\"stylesheet\" href=\"css/normalize.css\">
        <link rel=\"stylesheet\" href=\"css/site.css\">
    </head>
    <body>
        <header>
            <div class=\"container\">
                <div class=\"site-header\">
                    <a class=\"logo\" href=\"index.html\">SparkBlog</a>
                    <a class=\"new-entry button button-round\" href=\"new.html\"><i class=\"material-icons\">create</i></a>
                </div>
            </div>
        </header>
        <section>
            <div class=\"container\">
                <div class=\"entry-list\">
                    <article>
                        <h2><a href=\"detail.html\">The best day I’ve ever had</a></h2>
                        <time datetime=\"2016-01-31 1:00\">January 31, 2016 at 1:00</time>
                    </article>
                    <article>
                        <h2><a href=\"detail_2.html\">The absolute worst day I’ve ever had</a></h2>
                        <time datetime=\"2016-01-31 1:00\">January 31, 2016 at 1:00</a></time>
                    </article>
                    <article>
                        <h2><a href=\"detail_3.html\">That time at the mall</a></h2>
                        <time datetime=\"2016-01-31 1:00\">January 31, 2016 at 1:00</a></time>
                    </article>
                    <article>
                        <h2><a href=\"detail_4.html\">Dude, where’s my car?</a></h2>
                        <time datetime=\"2016-01-31 1:00\">January 31, 2016 at 1:00</a></time>
                    </article>
                </div>
            </div>
        </section>
        <footer>
            <div>
                <a href=\"#\">Contact Us</a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
                <a href=\"#\">Terms</a>
            </div>
        </footer>
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "home.php";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "home.php", "/Users/kaismapenn-titley/Documents/techdegree/project-05/app/templates/home.php");
    }
}
