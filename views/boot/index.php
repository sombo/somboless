<?php
//$server_name=$_SERVER['SERVER_NAME'] . ':81';
$server_name=$_SERVER['SERVER_NAME'];
$_serv="http://" . $server_name;
include_once ($_SERVER['DOCUMENT_ROOT']."/boot/functions_boot.php");

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap for PHP developers beta 0.1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

 <? include_once ($_SERVER['DOCUMENT_ROOT']."/boot/top.php");?>
  </head>

  <body>
<?
// MAKE NAVIGATION
$_nav['Home']="index.php";
$_nav['Page 1']="index.php";
$_nav['Page 2']="index.php";
$_nav['Page 3']="index.php";

// MAKE DROPDOWNS

$_ndd['Page 1']['Home']="index.php";
$_ndd['Page 1']['Page 1']="index.php";
$_ndd['Page 1']['Page 2']="index.php";
$_ndd['Page 1']['_divider']="";
$_ndd['Page 1']['Page 3']="index.php";
$_ndd['Page 1']['Page 4']="index.php";
$_ndd['Page 1']['_divider']="";// you can't have two dividers
$_ndd['Page 1']['Page 5']="index.php";

$_ndd['Page 2']['Page 5']="index.php";
$_ndd['Page 2']['_divider']="";
$_ndd['Page 2']['Page 6']="index.php";
$_nav_active='Page 1';

make_nav_simple ($_nav,$_nav_active,$_ndd);
?>
  <div class="container">


<header class="jumbotron subhead" id="overview">
  <h1>BOOTSTRAP FOR PHP DEVELOPERS </h1>
  <p class="lead">&quot;When i have an idea, I want to build the prototype as quick as posible.&quot;</p>
   <p class="lead">By Yianis Giannopoulos ... beta version 0.2</p>
   <p class="lead"><a href="http://code.google.com/p/bootstrap-for-php-developers/">Download from here</a></p>
    <p><? fb_like('http://www.keacode.com/boot'); ?></p>
<p>  This is a quick way to use bootstrap and other frameworks. Perfect for php application prototyping, no need to write any html or open the bootstrap documentation ! Download, and you are ready to start in 1 min.</p>
<p>23/02/12 - NEW FUNCTIONS AND BUG FIXES:</p>
<p>- share_this_page() , creates twiter, google+ and facebook sharing buttons.</p>
<p>- make_dropdown($nav,$_ndd); ... yeah ... it just makes the &lt;li&gt; for all the dropdowns and menus and its being used in the rest of the functions.</p>
<p>- small bug fixes that you never found :) ...</p>
<h1>Do you share ?</h1>
Simple ...
<? share_this_page(); ?> 
<pre>
share_this_page();
</pre>
<p>&nbsp;</p>
</header> 
<?
//make_nav_simple ($_nav,$_nav_active,$_dd);

?>
<h1>Starting a new project.</h1>
Simply copy paste this on the head of your page.
<pre>
include_once ($_SERVER['DOCUMENT_ROOT']."/boot/functions_boot.php");
include_once ($_SERVER['DOCUMENT_ROOT']."/boot/top.php");
</pre>
Then, add this at the bottom of your page just before closing the body tag.
<pre>
 include_once ($_SERVER['DOCUMENT_ROOT']."/boot/bot.php");
</pre>
It includes all the javascript libraries you will need, so you are ready to go. Once the project is ready, then you can remove the unwanted libraries from bot.php ;) ...

<h1>Top navigation</h1>
This is a special navigation. It has black background and it appears on the top of tha page. All you have to do is to crate two arrays, one for the main categories and another one with the dropdowns. If you skip the dropdown array you get a navigation panel without the dropdowns ;)

<pre>
// MAKE NAVIGATION
$_nav['Home']="index.php";
$_nav['Page 1']="index.php";
$_nav['Page 2']="index.php";
$_nav['Page 3']="index.php";
// MAKE DROPDOWNS

$_ndd['Page 1']['Home']="index.php";
$_ndd['Page 1']['Page 1']="index.php";
$_ndd['Page 1']['Page 2']="index.php";
$_ndd['Page 1']['_divider']="";
$_ndd['Page 1']['Page 3']="index.php";
$_ndd['Page 1']['Page 4']="index.php";
$_ndd['Page 1']['_divider']="";// you can't have two dividers
$_ndd['Page 1']['Page 5']="index.php";

$_ndd['Page 2']['Page 5']="index.php";
$_ndd['Page 2']['_divider']="";
$_ndd['Page 2']['Page 6']="index.php";
$_nav_active='Page 1';

make_nav_simple ($project_name,$_nav,$_nav_active,$_ndd)
</pre>
Bug: 

<h1>Other menus</h1>
By using the two arrays, we can create any menu we want with 1 command :)
<div class="subnav">

<?
	nav_dropdown($_nav,$_ndd,'nav-pills');
?>
</div>
Proudly made with
<pre>
	
    nav_dropdown($_nav,$_ndd,'nav-pills');
	
</pre>
<p>and also adding class="subnav", to give it the design of a sub-nav</p>

You just change the type and all happens atomatic. Posible types : nav-pills,nav-tabs,nav-pills nav-stacked,nav-tabs nav-stacked
<? 
	nav_dropdown($_nav,$_ndd,'nav-stacked');
?>
Proudly made with
<pre>
	nav_dropdown($_nav,$_ndd,'nav-stacked');
</pre>


Note that im using the same data with the top navigation.


<h1>Buttons</h1>
Who likes to remember that warning makes a red button ? Use this functions on your apps and use: red,blue,green,teal,orange.
<pre>
button('Red','#',red,$size);
</pre>
<?
button('Red','#','red',$size,'');
?>

<h1>Small tutorial on  layouts</h1>
<h2>Fixed layout</h2>
<div class="container">
   The default and simple 940px-wide, centered layout for just about any website or page provided by a single <pre>class="container"</pre>
  </div>
<h2>Fluid layout</h2>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2">
     This side bar has span2. gives flexible page structure, min- and max-widths, and a left-hand sidebar. It's great for apps and docs.
    </div>
    <div class="span10">
    This is SPAN 10.
    <pre>class="container-fluid"</pre> gives flexible page structure, min- and max-widths, and a left-hand sidebar. It's great for apps and docs.
 <br/> Remeber ... 10+2=12 !
    </div>
  </div>
</div>
<h1>Button dropdowns</h1>
<pre>
$_dd['Cyan']['Home']="index.php";
$_dd['Cyan']['Page 1']="index.php";
$_dd['Cyan']['Page 2']="index.php";
$_dd['Cyan']['_divider']="";
$_dd['Cyan']['Page 3']="index.php";
$_dd['Cyan']['Page 4']="index.php";
$_dd['Cyan']['_divider']="";
$_dd['Cyan']['Page 5']="index.php";

button('Cyan','#','teal',$size,$_dd);
</pre>
<?
$_dd['Cyan']['Home']="index.php";
$_dd['Cyan']['Page 1']="index.php";
$_dd['Cyan']['Page 2']="index.php";
$_dd['Cyan']['_divider']="";
$_dd['Cyan']['Page 3']="index.php";
$_dd['Cyan']['Page 4']="index.php";
$_dd['Cyan']['_divider']="";
$_dd['Cyan']['Page 5']="index.php";

button('Cyan','#','teal',$size,$_dd);
?>

<h1>Do you like ?</h1>
Fastest way to add a like button. It using the iframe solution.
<pre>
fb_like('http://www.keacode.com/boot');
</pre>
<p><? fb_like('http://www.keacode.com/boot'); ?></p>





 <!-- Footer
      ================================================== -->
      <footer class="footer">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>Designed and built with all the laziness in the world</p>
        <p>Code licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>. Documentation licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
        <p>Icons from <a href="http://glyphicons.com">Glyphicons Free</a>, licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
      </footer>


  </div> <!-- /container -->

 <? include_once ($_SERVER['DOCUMENT_ROOT']."/boot/bot.php");?>
 
  </body>
</html>
