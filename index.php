<?php
// --Config Start-- 
define('CLAIRE_TEXTMODE', false); //true disallow images.
define('CLAIRE_BLOGMODE', false); //true allow creating thread ony by admin & mod.
define('TINYIB_PAGETITLE', 'Claire Imageboard');
define('TINYIB_ADMINPASS',  "adminpassword");
define('TINYIB_MODPASS',    "modpassword"); // Leave blank to disable
define('TINYIB_THREADSPERPAGE', 13);
define('TINYIB_REPLIESTOSHOW',  3);
define('TINYIB_MAXTHREADS',     0);    // 0 disables deleting old threads
define('TINYIB_DELETE_TIMEOUT', 600);  // Seconds for deleting own posts
define('TINYIB_MAXPOSTSIZE',    16000); // Characters
define('TINYIB_RATELIMIT',      7);   // Delay between posts from same IP
define('TINYIB_TRIPSEED',   "1231");
define('TINYIB_USECAPTCHA',   false); // just use it.
define('TINYIB_CAPTCHASALT',  'CAPTCHASALT');
define('TINYIB_THUMBWIDTH',  200);
define('TINYIB_THUMBHEIGHT', 300);
define('TINYIB_REPLYWIDTH',  200);
define('TINYIB_REPLYHEIGHT', 300);
define('TINYIB_TIMEZONE',   ''); // Leave blank to use server default timezone
define('TINYIB_DATEFORMAT', 'D Y-m-d g:ia');
define('TINYIB_DBPOSTS','posts');
define('TINYIB_DBBANS', 'bans');
define('TINYIB_DBLOCKS','locked_threads');
define('TINYIB_DBPATH', '../database.db');
// --Config End--
session_start();if (!file_exists('db')){mkdir('db', 0777, true);}
error_reporting(E_ALL);
function pageHeader() {
        $page_title = TINYIB_PAGETITLE;
        $return = <<<EOF
<!DOCTYPE html>
<html>
        <head>
<style>
#postarea table{margin:0 auto;text-align:left}
#postarea,.login{text-align:center}
.abbrev{color:#707070}
.adminbar{clear:both;float:right;text-align:right}
.doubledash{clear:both;float:left;vertical-align:top}
.filesize{text-decoration:none}
.filetitle{background:inherit;color:#CC1105;font-size:1.2em}
.floatpost{clear:both;float:right}
.footer{clear:both;font-family:serif;font-size:12px;text-align:center}
.highlight{background:#F0E0D6;border:2px dashed #EA8;color:maroon}
.logo{clear:both;color:#666;font-size:2em;text-align:center;width:100%;margin:10px auto;}
.managebutton{font-size:15px;height:28px;margin:.2em}
.moderator{color:red}
.nothumb{background:#eee;border:2px dashed #aaa;float:left;margin:2px 20px;padding:1em .5em;text-align:center}
.omittedposts{color:#707070;font-style:italic}
.postblock{color:#fff;font-size:12px;font-weight:100;text-align:right}
.postername,.commentpostername{color:#117743}
.postertrip{color:#228854}
.reply{background:rgba(20,20,20,0.5);border:1px dashed #666;border-radius:2px;max-width:700px;padding:4px;word-wrap:break-word}
.reply .filesize{margin-left:20px}
.replyhl{background:#F0C0B0;color:maroon}
.replylink{float:right}
.replymode{background:#E04000;color:#FFF;padding:2px;text-align:center;width:100%}
.replytitle{color:#CC1105;font-size:1.2em}
.row1{background:#EEC;color:maroon}
.row2{background:#DDA;color:maroon}
.rules{font-size:6px;text-align:justify;width:300px}
.rules ul{margin:0;padding-left:0}
.spoiler{background-color:#CCC;color:#CCC}
.spoiler:hover{background-color:#000;color:#fff}
.thumb{border:none;float:left;margin:2px 20px}
.thumbnailmsg{color:maroon;font-size:small}
.unkfunc{background:inherit;color:#789922}
a,a{color:#CCC;text-decoration:none}
a:hover{color:#fff;text-decoration:underline}
blockquote blockquote{margin-left:0}
body{color:#CCC;font-weight:100;margin:auto;width:800px}
form,.reply blockquote,blockquote :last-child{margin-bottom:0}
html{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAABlBMVEUcHBweHh5WWmVNAAAAJUlEQVR4AWMAAkYQgNOMaCJAAlUEiFFFgAhVBM1EICAoMljcAQBWEAChK8hEDAAAAABJRU5ErkJggg==);font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;font-size:14px;height:100%;margin:0;padding:0;width:100%}
input[type="text"],textarea,select{background:rgba(20,20,20,0.5);border:1px solid #999;color:#fff}
input[type=checkbox]{vertical-align:bottom}
</style>
                <title>{$page_title}</title>
                <meta http-equiv="content-type" content="text/html;charset=UTF-8">
                <meta http-equiv="pragma" content="no-cache">
                <meta http-equiv="expires" content="-1">
                <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
                <script>function insertTag(e,t){var n=document.getElementsByName("message")[0];var r=n.selectionStart;var i=n.selectionEnd;var s=n.value.length;var o=e+n.value.substring(r,i)+t;n.value=n.value.substring(0,r)+o+n.value.substring(i,s)}function quote(e){var t=document.forms.postform.message;e=">>"+e;if(t){if(t.createTextRange&&t.caretPos){var n=t.caretPos;n.text=n.text.charAt(n.text.length-1)==" "?e+" ":e}else if(t.setSelectionRange){var r=t.selectionStart;var i=t.selectionEnd;t.value=t.value.substr(0,r)+e+t.value.substr(i);t.setSelectionRange(r+e.length,r+e.length)}else{t.value+=e+" "}}}</script>
        </head>
EOF;
        return $return;
}
function pageFooter() {
        return <<<EOF
</div>
        </div>
        </body>
</html>
EOF;
}
function buildPost($post, $isrespage) {
$post = preg_replace("#\*\*(.*?)\*\*#","<b>\\1</b>",$post);
    $post = preg_replace("#\[s\](.*?)\[/s\]#","<strike>\\1</strike>",$post);
    $post = preg_replace("#\*(.*?)\*#","<i>\\1</i>",$post);
    $post = preg_replace("#\[u\](.*?)\[/u\]#","<span style=\"border-bottom: 1px solid\">\\1</span>",$post);
    $post = preg_replace("#\%\%(.*?)\%\%#","<span class=\"spoiler\">\\1</span>",$post);
    $post = preg_replace("#\'\'(.*?)\'\'#","<pre style=\"font-family: Courier New, Courier, mono\">\\1</pre>",$post);
        $return = "";
        $threadid = ($post['parent'] == 0) ? $post['id'] : $post['parent'];
        $postlink = '?do=thread&id='.$threadid.'#'.$post['id'];
        $image_desc = '';
        if ($post['file'] != '') {
                $image_desc =
                        cleanString($post['file_original']) .' ('.$post["image_width"].'x'.
                        $post["image_height"].', '.$post["file_size_formatted"].')'
                ;
        }
        if ($post['parent'] == 0 && !$isrespage) {
                $note = isLocked($threadid) ? '<em>(locked)</em>' : ''; //&#x1f512;
                $return .=
                        "<span class=\"replylink\">${note}[<a href=\"?do=thread&id=${post["id"]}\">".
                        "View thread</a>]&nbsp;</span>"
                ;
        }
        if ($post["parent"] != 0) {
                $return .= <<<EOF
<table>
        <tbody>
                <tr>
                        <td class="doubledash">&gt;&gt;</td>
                        <td class="reply" id="reply${post["id"]}">
EOF;
        } elseif ($post["file"] != "") {
                $return .= <<<EOF
<a target="_blank" href="db/${post["file"]}">
        <span id="thumb${post['id']}"><img title="$image_desc" src="db/${post["thumb"]}" alt="${post["id"]}" class="thumb" width="${post["thumb_width"]}" height="${post["thumb_height"]}"></span>
</a>
EOF;
        }
        $return .= <<<EOF
<a href="?do=delpost&id={$post['id']}" title="Delete" />X</a> <a name="${post['id']}"></a>
EOF;
        if ($post["subject"] != "") {
                $return .= "    <span class=\"filetitle\">${post["subject"]}</span> ";
        }
        $return .= <<<EOF
${post["nameblock"]} 

EOF;
        if (IS_ADMIN) {
                $return .= ' [<a href="?do=manage&p=bans&bans='.urlencode($post['ip']).'" title="Ban poster">'.htmlspecialchars($post['ip']).'</a>]';
        }
        $return .= <<<EOF
<span class="reflink">
        <a href="$postlink">No.</a><a href="javascript:quote('${post["id"]}')">${post['id']}</a>
</span>
EOF;
        if ($post['parent'] != 0 && $post["file"] != "") {
                $return .= <<<EOF
<br>
<a target="_blank" href="db/${post["file"]}">
        <span id="thumb${post["id"]}"><img title="$image_desc" src="db/${post["thumb"]}" alt="${post["id"]}" class="thumb" width="${post["thumb_width"]}" height="${post["thumb_height"]}"></span>
</a>
EOF;
        }
        $return .= <<<EOF
<blockquote>
{$post['message']}
</blockquote>
EOF;
        if ($post['parent'] == 0) {
                if (!$isrespage && $post["omitted"] > 0) {
                        $return .=
                                '<span class="omittedposts">'.$post['omitted'].' post(s) omitted. '.
                                '<a href="?do=thread&id='.$post["id"].'">Click here</a> to view.</span>'
                        ;
                }
        } else {
                $return .= <<<EOF
                        </td>
                </tr>
        </tbody>
</table>
EOF;
        }
        return $return;
}
function buildPostBlock($parent) {
if (CLAIRE_BLOGMODE) {
        if ($parent) {
        $body = '

                <div id="postarea">
                        <form name="postform" id="postform" action="?do=post" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="parent" value="'.htmlspecialchars($parent).'">
                        <table class="postform">
                                <tbody><tr><td class="postblock" title="Optional [#password]">Name</td>
                                <td><input type="text" name="name" size="28" maxlength="75">
                                </td></tr>
        ';
        if (! $parent) {
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Optional">Subject</td>
                                                <td>
                                                        <input type="text" name="subject" size="40" maxlength="75">
                                                </td>
                                        </tr>
                ';
        }
        $body .= '
                                        <tr>
                                                <td class="postblock">Message</td>
                                                <td>
                                                        <textarea name="message" cols="48" rows="4" placeholder=""></textarea>
                                                </td>
                                        </tr>
        ';
        if (TINYIB_USECAPTCHA && !LOGGED_IN) {
                $captcha_key = md5(mt_rand());
                $captcha_expect = md5(TINYIB_CAPTCHASALT.substr(md5($captcha_key),0,4));
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Please copy the text to show you\'re a human.">
                                                        <img src="captcha_png.php?key='.$captcha_key.'" />
                                                </td>
                                                <td>
                                                        <input type="hidden" name="captcha_ex" value="'.$captcha_expect.'" />
                                                        <input type="text" name="captcha_out" size="8" />
                                                </td>
                                        </tr>
                ';
        } if (!CLAIRE_TEXTMODE) {
        $body .= '
                                        <tr>
                                                <td class="postblock">Image</td>
                                                <td>
                                                <input type="file" name="file" size="35" title="Images may be GIF, JPG or PNG up to 2 MB.">';
                                                        }$body .= '
                                                </td>
                                        </tr>
        ';
        $post_button_name = ($parent) ? 'Post Reply' : 'Create Thread';
        $opt_bump_thread = ($parent) ? '<label><input type="checkbox" name="bump" id="bump" checked>Bump</label>' : '';
        $opt_modpost = LOGGED_IN ? '<label><input type="checkbox" name="modpost" id="modpost">Modpost</label>' : '';
        $opt_rawhtml = LOGGED_IN ? '<label><input type="checkbox" name="rawhtml" id="rawhtml">RawHTML</label>' : '';
        $body .= '
                                        <tr>
                                                <td class="postblock"></td>
                                                <td>
                                                        <input type="submit" value="'.$post_button_name.'">
                                                        '.$opt_bump_thread.'
                                                        '.$opt_modpost.'
                                                        '.$opt_rawhtml.'
                                                </td>
                                        </tr>
                                </tbody>
                        </table>
                        </form>
                </div>
                <hr>
        ';
        return $body;
        }

elseif (!$parent && LOGGED_IN) {
        $body = '
                <div id="postarea">
                        <form name="postform" id="postform" action="?do=post" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="parent" value="'.htmlspecialchars($parent).'">
                        <table class="postform">
                                <tbody>
                                        <tr>
                                                <td class="postblock" title="Optional [#password]">Name</td>
                                                <td>
                                                        <input type="text" name="name" size="28" maxlength="75">
                                                </td>
                                        </tr>
        ';
        if (! $parent) {
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Optional">Subject</td>
                                                <td>
                                                        <input type="text" name="subject" size="40" maxlength="75">
                                                </td>
                                        </tr>
                ';
        }
        $body .= '
                                        <tr>
                                                <td class="postblock">Message</td>
                                                <td>
                                                        <textarea name="message" cols="48" rows="4" placeholder=""></textarea>
                                                </td>
                                        </tr>
        ';
        if (TINYIB_USECAPTCHA && !LOGGED_IN) {
                $captcha_key = md5(mt_rand());
                $captcha_expect = md5(TINYIB_CAPTCHASALT.substr(md5($captcha_key),0,4));
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Please copy the text to show you\'re a human.">
                                                        <img src="captcha_png.php?key='.$captcha_key.'" />
                                                </td>
                                                <td>
                                                        <input type="hidden" name="captcha_ex" value="'.$captcha_expect.'" />
                                                        <input type="text" name="captcha_out" size="8" />
                                                </td>
                                        </tr>
                ';
        } if (!CLAIRE_TEXTMODE) {
        $body .= '
                                        <tr>
                                                <td class="postblock">Image</td>
                                                <td>
                                                <input type="file" name="file" size="35" title="Images may be GIF, JPG or PNG up to 2 MB.">';
                                                        }$body .= '
                                                </td>
                                        </tr>
        ';
        $post_button_name = ($parent) ? 'Post Reply' : 'Create Thread';
        $opt_bump_thread = ($parent) ? '<label><input type="checkbox" name="bump" id="bump" checked>Bump</label>' : '';
        $opt_modpost = LOGGED_IN ? '<label><input type="checkbox" name="modpost" id="modpost">Modpost</label>' : '';
        $opt_rawhtml = LOGGED_IN ? '<label><input type="checkbox" name="rawhtml" id="rawhtml">RawHTML</label>' : '';
        $body .= '
                                        <tr>
                                                <td class="postblock"></td>
                                                <td>
                                                        <input type="submit" value="'.$post_button_name.'">
                                                        '.$opt_bump_thread.'
                                                        '.$opt_modpost.'
                                                        '.$opt_rawhtml.'
                                                </td>
                                        </tr>
                                </tbody>
                        </table>
                        </form>
                </div>
                <hr>
        ';
        return $body;
        }
        else{}
 
 
        }
else {
$body = '
                <div id="postarea">
                        <form name="postform" id="postform" action="?do=post" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="parent" value="'.htmlspecialchars($parent).'">
                        <table class="postform">
                                <tbody><tr><td class="postblock" title="Optional [#password]">Name</td>
                                <td><input type="text" name="name" size="28" maxlength="75">
                                </td></tr>
        ';
        if (! $parent) {
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Optional">Subject</td>
                                                <td>
                                                        <input type="text" name="subject" size="40" maxlength="75">
                                                </td>
                                        </tr>
                ';
        }
        $body .= '
                                        <tr>
                                                <td class="postblock">Message</td>
                                                <td>
                                                        <textarea name="message" cols="48" rows="4" placeholder=""></textarea>
                                                </td>
                                        </tr>
        ';
        if (TINYIB_USECAPTCHA && !LOGGED_IN) {
                $captcha_key = md5(mt_rand());
                $captcha_expect = md5(TINYIB_CAPTCHASALT.substr(md5($captcha_key),0,4));
                $body .= '
                                        <tr>
                                                <td class="postblock" title="Please copy the text to show you\'re a human.">
                                                        <img src="captcha_png.php?key='.$captcha_key.'" />
                                                </td>
                                                <td>
                                                        <input type="hidden" name="captcha_ex" value="'.$captcha_expect.'" />
                                                        <input type="text" name="captcha_out" size="8" />
                                                </td>
                                        </tr>
                ';
        } if (!CLAIRE_TEXTMODE) {
        $body .= '
                                        <tr>
                                                <td class="postblock">Image</td>
                                                <td>
                                                <input type="file" name="file" size="35" title="Images may be GIF, JPG or PNG up to 2 MB.">';
                                                        }$body .= '
                                                </td>
                                        </tr>
        ';
        $post_button_name = ($parent) ? 'Post Reply' : 'Create Thread';
        $opt_bump_thread = ($parent) ? '<label><input type="checkbox" name="bump" id="bump" checked>Bump</label>' : '';
        $opt_modpost = LOGGED_IN ? '<label><input type="checkbox" name="modpost" id="modpost">Modpost</label>' : '';
        $opt_rawhtml = LOGGED_IN ? '<label><input type="checkbox" name="rawhtml" id="rawhtml">RawHTML</label>' : '';
        $body .= '
                                        <tr>
                                                <td class="postblock"></td>
                                                <td>
                                                        <input type="submit" value="'.$post_button_name.'">
                                                        '.$opt_bump_thread.'
                                                        '.$opt_modpost.'
                                                        '.$opt_rawhtml.'
                                                </td>
                                        </tr>
                                </tbody>
                        </table>
                        </form>
                </div>
                <hr>
        ';
        return $body;
        }
        }

function buildPage($htmlposts, $parent, $pages=0, $thispage=0) {
        $locked = $parent ? isLocked($parent) : false;
        $returnlink = ''; $pagelinks = '';
        if ($parent == 0) {
                $pages = max($pages, 0);
                $pagelinks =
                        ($thispage == 0) ?
                        "[ Previous ]" :
                        '[ <a href="?do=page&p=' .($thispage-1). '">Previous</a> ]'
                ;              
                for ($i = 0;$i <= $pages;$i++) {
                        $pagelinks .= ($thispage == $i) ? "[ $i ]" : "[ <a href=\"?do=page&p=$i\">$i</a> ]";
                }              
                $pagelinks .= ($pages <= $thispage) ?
                        "[ Next ]" :
                        '[ <a href="?do=page&p='.($thispage+1). '">Next</a> ]'
                ;
        } else {
                $returnlink = '<span class="replylink">[<a href="?">Return</a>';
                if (LOGGED_IN) {
                        if ($locked) {
                                $returnlink .= ' | <a href="?do=lock&id='.$parent.'">Unlock Thread</a>';
                        } else {
                                $returnlink .= ' | <a href="?do=lock&id='.$parent.'">Lock Thread</a>';                         
                        }
                }
                $returnlink .= ']</span>';
        }
$body = '
<body>

<div class="logo"><a style="font-size:1em;" href="/">'.TINYIB_PAGETITLE.'</a></div>
<br>

        ';
        if ($locked) {
                $body .= '<div class="replymode">This thread is locked. You can\'t reply any more.</div>';
        }
        if ($parent) {
                $body .= $returnlink . "\n" . $htmlposts;
        }
        if (!$locked) {
                $body .= buildPostBlock($parent);
        }
        if (!$parent) {
                $body .= $returnlink . "\n" . $htmlposts;
        }
        $body .= <<<EOF
<div class="adminbar">Powered by: <a href="https://github.com/ClaireIsAlive/Claire">Claire</a></div>
               <div class="pagelinks">
                        $pagelinks
                </div>
                <br>
EOF;
        return pageHeader() . $body . pageFooter();
}
function viewPage($pagenum) {
        $page = intval($pagenum);
        $pagecount = max(0, ceil(countThreads() / TINYIB_THREADSPERPAGE) - 1);
        if (!is_numeric($pagenum) || $page < 0 || $page > $pagecount) fancyDie('Invalid page number.');
        $htmlposts = array();
        $threads = getThreadRange(TINYIB_THREADSPERPAGE, $pagenum * TINYIB_THREADSPERPAGE );
        foreach ($threads as $thread) {
                $replies = latestRepliesInThreadByID($thread['id']);
                $htmlreplies = array();
                foreach ($replies as $reply) {
                        $htmlreplies[] = buildPost($reply, False);
                }
                $thread["omitted"] = (count($htmlreplies) == 3) ? (count(postsInThreadByID($thread['id'])) - 4) : 0;
                $htmlposts[] = buildPost($thread, false) . implode("", array_reverse($htmlreplies)) . "<br clear=\"left\">\n<hr>";
        }
        return buildPage(implode('', $htmlposts), 0, $pagecount, $page);
}
function viewThread($id) {
        $htmlposts = array();
        $posts = postsInThreadByID($id);
        foreach ($posts as $post) $htmlposts[] = buildPost($post, True);
        $htmlposts[] = "<br clear=\"left\">\n<hr>";
        return buildPage(implode('',$htmlposts), $id);
}
function adminBar() {
        if (! LOGGED_IN) { return '[<a href="?">Return</a>]'; }
        $text = IS_ADMIN ? '[<a href="?do=manage&p=bans">Bans</a>] ' : '';
        $text .=
                '[<a href="?do=manage&p=threads">Thread list</a>] '.
                '[<a href="?do=manage&p=moderate">Moderate Post</a>] '.
                '[<a href="?do=manage&p=logout">Log Out</a>] '.
                '[<a href="?">Return</a>]'
        ;
        return $text;
}
function managePage($text) {
        $adminbar = adminBar();
        $body = <<<EOF
        <body>
                <div class="adminbar">
                        $adminbar
                </div>
                <div class="logo">
                </div>
                <hr width="90%" size="1">
                <div class="replymode">Manage mode</div>
                $text
                <hr>
EOF;
        return pageHeader() . $body . pageFooter();
}
function manageLogInForm() {
        return <<<EOF
        <form id="tinyib" name="tinyib" method="post" action="?do=manage&p=home">
                <fieldset>
                        <legend align="center">Please enter an administrator or moderator password</legend>
                        <div class="login">
                                <input type="password" id="password" name="password" autofocus><br>
                                <input type="submit" value="Submit" class="managebutton">
                        </div>
                </fieldset>
        </form>
        <br/>
EOF;
}
function manageBanForm() {
        $banstr = isset($_GET['bans']) ? $_GET['bans'] : '';
        return <<<EOF
        <form id="tinyib" name="tinyib" method="post" action="?do=manage&p=bans">
                <fieldset>
                        <legend>Ban an IP address from posting</legend>
                        <label for="ip">IP Address:</label>
                        <input type="text" name="ip" id="ip" value="$banstr" autofocus>
                        <input type="submit" value="Submit" class="managebutton">
                        <br/>
                        <label for="expire">Expire(sec):</label>
                        <input type="text" name="expire" id="expire" value="0">&nbsp;&nbsp;
                        <small>
                                <a href="#" onclick="document.tinyib.expire.value='3600';return false;">1hr</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='86400';return false;">1d</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='172800';return false;">2d</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='604800';return false;">1w</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='1209600';return false;">2w</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='2592000';return false;">30d</a>&nbsp;
                                <a href="#" onclick="document.tinyib.expire.value='0';return false;">never</a>
                        </small>
                        <br/>
                        <label for="reason">Reason:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="reason" id="reason">&nbsp;&nbsp;<small>(optional)</small>
                </fieldset>
        </form>
        <br/>
EOF;
}
function manageBansTable() {
        $text = '';
        $allbans = allBans();
        if (count($allbans) > 0) {
                $text .= '<table border="1"><tr><th>IP Address</th><th>Set At</th><th>Expires</th><th>Reason Provided</th><th>&nbsp;</th></tr>';
                foreach ($allbans as $ban) {
                        $expire = ($ban['expire'] > 0) ? date('y/m/d(D)H:i:s', $ban['expire']) : 'Never';
                        $reason = ($ban['reason'] == '') ? '&nbsp;' : htmlentities($ban['reason']);
                        $text .= '<tr><td>' . $ban['ip'] . '</td><td>' . date('y/m/d(D)H:i:s', $ban['timestamp']) . '</td><td>' . $expire . '</td><td>' . $reason . '</td><td><a href="?do=manage&p=bans&lift=' . $ban['id'] . '">lift</a></td></tr>';
                }
                $text .= '</table>';
        }
        return $text;
}
function manageModeratePostForm() {
        return <<<EOF
        <form id="tinyib" name="tinyib" method="get" action="?">
                <input type="hidden" name="manage" value="">
                <fieldset>
                        <legend>Moderate a post</legend>
                        <input type="hidden" name="do" value="manage">
                        <input type="hidden" name="p" value="moderate">
                        <label for="moderate">Post ID:</label>
                        <input type="text" name="moderate" id="moderate" autofocus>
                        <input type="submit" value="Submit" class="managebutton">
                        <br/>
                </fieldset>
        </form>
        <br/>
EOF;
}
function manageModeratePost($post) {
        $ban = banByIP($post['ip']);
        $ban_disabled = (!$ban && IS_ADMIN) ? '' : ' disabled';
        $ban_disabled_info = (!$ban) ? '' : (' A ban record already exists for ' . $post['ip']);
        $post_html = buildPost($post, true);
        $post_or_thread = ($post['parent'] == 0) ? 'Thread' : 'Post';
        return <<<EOF
        <fieldset>
                <legend>Moderating post No.${post['id']}</legend>              
                <div class="floatpost">
                        <fieldset>
                                <legend>$post_or_thread</legend>       
                                $post_html
                        </fieldset>
                </div>         
                <fieldset>
                        <legend>Action</legend>                                
                        <form method="get" action="?">
                                <input type="hidden" name="do" value="manage" />
                                <input type="hidden" name="p" value="delete" />
                                <input type="hidden" name="delete" value="${post['id']}" />
                                <input type="submit" value="Delete $post_or_thread" class="managebutton" />
                        </form>
                        <br/>
                        <form method="get" action="?">
                                <input type="hidden" name="do" value="manage" />
                                <input type="hidden" name="p"  value="bans" />
                                <input type="hidden" name="bans" value="${post['ip']}" />
                                <input type="submit" value="Ban Poster" class="managebutton"$ban_disabled />$ban_disabled_info
                        </form>
                </fieldset>    
        </fieldset>
        <br />
EOF;
}
function manageAllThreads() {
        $threads = getThreadRange(10000, 0);
        $locks   = getAllLocks();
        $ret = '
                <table style="width:100%;border:0px;border-collapse:collapse;margin:2px;">
                        <thead style="background-color:darkred;color:white;text-align:left;">
                                <tr>                                   
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>First post</th>
                                        <th style="width:160px;">Created</th>
                                        <th style="width:160px;">Last Bump</th>
                                        <th>Locked</th>
                                </tr>
                        </thead>
                        <tbody>
        ';
        foreach($threads as $thread) {
                $locked = in_array($thread['id'], $locks);
                // Workaround for incorrectly imported history
                $bump = ($thread['bumped'] > 1000 ? date(TINYIB_DATEFORMAT,$thread['bumped']) : '-');
                $ret .= '
                                <tr>
                                        <td><a href="?do=thread&id='.$thread['id'].'">#'.$thread['id'].'</a></td>
                                        <td>'.$thread['subject'].'</td>
                                        <td>'.htmlspecialchars(substr($thread['message'], 0, 60)).'</td>
                                        <td>'.date(TINYIB_DATEFORMAT, $thread['timestamp']).'</td>
                                        <td><a href="?do=manage&p=bump&id='.$thread['id'].'" title="Bump this thread">'.$bump.'</a></td>
                                        <td>'.($locked ? 'Locked' : '-').'</td>
                                </tr>
                ';
        }
        $ret .= '
                        </tbody>
                </table>
        ';
        return $ret;
}
function cleanString($string) {
        return str_replace(array("<", ">", '"'), array("&lt;", "&gt;", "&quot;"), $string);
}
function fancyDie($message, $depth=1) {
        die('<!DOCTYPE html>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="/claire.css" title="claire"/>
        <link rel="alternate stylesheet" type="text/css" href="/futaba.css" title="futaba"/>
                <script type="text/javascript" src="/switch.js"></script>
<body>

<br>
        '.str_replace("\n", '<br>', $message).'
        

<div class="adminbar">Powered by: <a href="https://github.com/ClaireIsAlive/Claire">Claire</a></div>
</body>
        ');
}
function newPost() {
        return array(
                'parent' => '0',
                'timestamp' => '0',
                'bumped' => '0',
                'ip' => '',
                'name' => '',
                'tripcode' => '',
                'email' => '',
                'nameblock' => '',
                'subject' => '',
                'message' => '',
                'password' => '',
                'file' => '',
                'file_hex' => '',
                'file_original' => '',
                'file_size' => '0',
                'file_size_formatted' => '',
                'image_width' => '0',
                'image_height' => '0',
                'thumb' => '',
                'thumb_width' => '0',
                'thumb_height' => '0'
        );
}
function convertBytes($number) {
        $len = strlen($number);
        if ($len <= 3) return sprintf("%dB",     $number);
        if ($len <= 6) return sprintf("%0.2fKB", $number/1024);
        if ($len <= 9) return sprintf("%0.2fMB", $number/1024/1024);
        return sprintf("%0.2fGB", $number/1024/1024/1024);                                             
}
function nameAndTripcode($name) {
        if (preg_match("/(#|!)(.*)/", $name, $regs)) {
                $cap = $regs[2];
                $cap_full = '#' . $regs[2];
                if (function_exists('mb_convert_encoding')) {
                        $recoded_cap = mb_convert_encoding($cap, 'SJIS', 'UTF-8');
                        if ($recoded_cap != '') {
                                $cap = $recoded_cap;
                        }
                }
                if (strpos($name, '#') === false) {
                        $cap_delimiter = '!';
                } elseif (strpos($name, '!') === false) {
                        $cap_delimiter = '#';
                } else {
                        $cap_delimiter = (strpos($name, '#') < strpos($name, '!')) ? '#' : '!';
                }
                if (preg_match("/(.*)(" . $cap_delimiter . ")(.*)/", $cap, $regs_secure)) {
                        $cap = $regs_secure[1];
                        $cap_secure = $regs_secure[3];
                        $is_secure_trip = true;
                } else {
                        $is_secure_trip = false;
                }
                $tripcode = "";
                if ($cap != "") { // Copied from Futabally
                        $cap = strtr($cap, "&amp;", "&");
                        $cap = strtr($cap, "&#44;", ", ");
                        $salt = substr($cap."H.", 1, 2);
                        $salt = preg_replace("/[^\.-z]/", ".", $salt);
                        $salt = strtr($salt, ":;<=>?@[\\]^_`", "ABCDEFGabcdef");
                        $tripcode = substr(crypt($cap, $salt), -10);
                }
                if ($is_secure_trip) {
                        if ($cap != "") {
                                $tripcode .= "!";
                        }
                        $tripcode .= "!" . substr(md5($cap_secure . TINYIB_TRIPSEED), 2, 10);
                }
                return array(preg_replace("/(" . $cap_delimiter . ")(.*)/", "", $name), $tripcode);
        }
        return array($name, "");
}
function nameBlock($name, $tripcode, $email, $timestamp, $modposttext) {
        $output = '<span class="postername">';
        $output .= ($name == "" && $tripcode == "") ? "Anonymous" : $name;
        if ($tripcode != "") {
                $output .= '</span><span class="postertrip">!' . $tripcode;
        }
        $output .= '</span>';
        if ($email != "") {
                $output = '<a href="mailto:' . $email . '">' . $output . '</a>';
        }
        return $output . $modposttext . ' ' . date(TINYIB_DATEFORMAT, $timestamp);
}
function _postLink($matches) {
        $post = postByID($matches[1]);
        if ($post) {
                return
                        '<a href="?do=thread&id=' .
                        ($post['parent'] == 0 ? $post['id'] : $post['parent']) .
                        '#' . $matches[1] . '">' . $matches[0] . '</a>'
                ;
        }
        return $matches[0];
}
function postLink($message) {
        return preg_replace_callback('/&gt;&gt;([0-9]+)/', '_postLink', $message);
}
function colorQuote($message) {
        if (substr($message, -1, 1) != "\n") { $message .= "\n"; }
        return preg_replace('/^(&gt;[^\>](.*))\n/m', '<span class="unkfunc">\\1</span>' . "\n", $message);
}
function deletePostImages($post) {
        if ($post['file'] != '') { @unlink('db/' . $post['file']); }
        if ($post['thumb'] != '') { @unlink('db/' . $post['thumb']); }
}
function checkBanned() {
        $ban = banByIP($_SERVER['REMOTE_ADDR']);
        if ($ban) {
                if ($ban['expire'] == 0 || $ban['expire'] > time()) {
                        $expire = ($ban['expire'] > 0) ?
                                ('Your ban will expire ' . date(TINYIB_DATEFORMAT, $ban['expire'])) :
                                'The ban on your IP address is permanent and will not expire.'
                        ;
                        $reason = ($ban['reason'] == '') ?
                                '' :
                                ('<br>The reason provided was: ' . $ban['reason'])
                        ;
                        fancyDie('Sorry, it appears that you have been banned from posting on this image board.  ' . $expire . $reason);
                } else {
                        clearExpiredBans();
                }
        }
}
function checkFlood() {
        $lastpost = lastPostByIP();
        if ($lastpost) {
                if ((time() - $lastpost['timestamp']) < TINYIB_RATELIMIT) {
                        fancyDie(
                                'Please wait a moment before posting again. '.
                                ' You will be able to make another post in ' .
                                (TINYIB_RATELIMIT - (time() - $lastpost['timestamp'])) .
                                " second(s)."
                        );
                }
        }
}
function checkMessageSize() {
        if (strlen($_POST["message"]) > TINYIB_MAXPOSTSIZE) {
                fancyDie(
                        'Your message is ' . strlen($_POST["message"]) .
                        ' characters long, but the maximum allowed is '.TINYIB_MAXPOSTSIZE.
                        '.<br>Please shorten your message, or post it in multiple parts.'
                );
        }
}
function manageCheckLogIn() {
        $loggedin = false; $isadmin = false;
        if (isset($_POST['password'])) {
                if ($_POST['password'] == TINYIB_ADMINPASS) {
                        $_SESSION['tinyib'] = TINYIB_ADMINPASS;
                } elseif (TINYIB_MODPASS != '' && $_POST['password'] == TINYIB_MODPASS) {
                        $_SESSION['tinyib'] = TINYIB_MODPASS;
                }
        }
        if (isset($_SESSION['tinyib'])) {
                if ($_SESSION['tinyib'] == TINYIB_ADMINPASS) {
                        $loggedin = true;
                        $isadmin = true;
                } elseif (TINYIB_MODPASS != '' && $_SESSION['tinyib'] == TINYIB_MODPASS) {
                        $loggedin = true;
                }
        }
        return array($loggedin, $isadmin);
}
function setParent() {
        if (isset($_POST["parent"])) {
                if ($_POST["parent"] != "0") {
                        if (!threadExistsByID($_POST['parent'])) {
                                fancyDie("Invalid parent thread ID - unable to create post.");
                        }                      
                        return $_POST["parent"];
                }
        }      
        return "0";
}
function validateFileUpload() {
        switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                        break;
                case UPLOAD_ERR_FORM_SIZE:
                        fancyDie("That file is larger than 2 MB.");
                        break;
                case UPLOAD_ERR_INI_SIZE:
                        fancyDie("The uploaded file exceeds the upload_max_filesize directive (" . ini_get('upload_max_filesize') . ") in php.ini.");
                        break;
                case UPLOAD_ERR_PARTIAL:
                        fancyDie("The uploaded file was only partially uploaded.");
                        break;
                case UPLOAD_ERR_NO_FILE:
                        fancyDie("No file was uploaded.");
                        break;
                case UPLOAD_ERR_NO_TMP_DIR:
                        fancyDie("Missing a temporary folder.");
                        break;
                case UPLOAD_ERR_CANT_WRITE:
                        fancyDie("Failed to write file to disk");
                        break;
                default:
                        fancyDie("Unable to save the uploaded file.");
        }
}
function checkDuplicateImage($hex) {
        $hexmatches = postsByHex($hex);
        if (count($hexmatches) > 0) {
                foreach ($hexmatches as $hexmatch) {
                        $location = ($hexmatch['parent']=='0') ? $hexmatch['id'] : $hexmatch['parent'];
                        fancyDie(
                                'TIME PARADOX! That file has already been posted '.
                                '<a href="?do=thread&id='.$location.'#'.$hexmatch['id'].'">here</a>.
                                <br>'
                        );
                }
        }
}
function thumbnailDimensions($width, $height, $is_reply) {
        if ($is_reply) {
                $max_h = TINYIB_REPLYHEIGHT;
                $max_w = TINYIB_REPLYWIDTH;
        } else {
                $max_h = TINYIB_THUMBHEIGHT;
                $max_w = TINYIB_THUMBWIDTH;
        }
        return ($width > $max_w || $height > $max_h) ? array($max_w, $max_h) : array($width, $height);
}
function createThumbnail($name, $filename, $new_w, $new_h) {
	$system = explode(".", $filename);
	$system = array_reverse($system);
	if (preg_match("/jpg|jpeg/", $system[0])) {
		$src_img = imagecreatefromjpeg($name);
	} else if (preg_match("/png/", $system[0])) {
		$src_img = imagecreatefrompng($name);
	} else if (preg_match("/gif/", $system[0])) {
		$src_img = imagecreatefromgif($name);
	} else {
		return false;
	}

	if (!$src_img) {
		fancyDie("Unable to read uploaded file during thumbnailing. A common cause for this is an incorrect extension when the file is actually of a different type.");
	}
	$old_x = imageSX($src_img);
	$old_y = imageSY($src_img);
	$percent = ($old_x > $old_y) ? ($new_w / $old_x) : ($new_h / $old_y);
	$thumb_w = round($old_x * $percent);
	$thumb_h = round($old_y * $percent);

	$dst_img = imagecreatetruecolor($thumb_w, $thumb_h);
	if (preg_match("/png/", $system[0]) && imagepng($src_img, $filename)) {
		imagealphablending($dst_img, false);
		imagesavealpha($dst_img, true);

		$color = imagecolorallocatealpha($dst_img, 0, 0, 0, 0);
		imagefilledrectangle($dst_img, 0, 0, $thumb_w, $thumb_h, $color);
		imagecolortransparent($dst_img, $color);

		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
	} else {
		fastimagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
	}

	if (preg_match("/png/", $system[0])) {
		if (!imagepng($dst_img, $filename)) {
			return false;
		}
	} else if (preg_match("/jpg|jpeg/", $system[0])) {
		if (!imagejpeg($dst_img, $filename, 70)) {
			return false;
		}
	} else if (preg_match("/gif/", $system[0])) {
		if (!imagegif($dst_img, $filename)) {
			return false;
		}
	}

	imagedestroy($dst_img);
	imagedestroy($src_img);

	return true;
}

function fastimagecopyresampled(&$dst_image, &$src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) {
	// Author: Tim Eckel - Date: 12/17/04 - Project: FreeRingers.net - Freely distributable.
	if (empty($src_image) || empty($dst_image)) {
		return false;
	}

	if ($quality <= 1) {
		$temp = imagecreatetruecolor($dst_w + 1, $dst_h + 1);

		imagecopyresized($temp, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w + 1, $dst_h + 1, $src_w, $src_h);
		imagecopyresized($dst_image, $temp, 0, 0, 0, 0, $dst_w, $dst_h, $dst_w, $dst_h);
		imagedestroy($temp);
	} elseif ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
		$tmp_w = $dst_w * $quality;
		$tmp_h = $dst_h * $quality;
		$temp = imagecreatetruecolor($tmp_w + 1, $tmp_h + 1);

		imagecopyresized($temp, $src_image, $dst_x * $quality, $dst_y * $quality, $src_x, $src_y, $tmp_w + 1, $tmp_h + 1, $src_w, $src_h);
		imagecopyresampled($dst_image, $temp, 0, 0, 0, 0, $dst_w, $dst_h, $tmp_w, $tmp_h);
		imagedestroy($temp);
	} else {
		imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	}

	return true;
}
function redirect($url='?do=page&p=0') {
        header('Location: '.$url);
        die();
}
try {
        $db = new PDO('sqlite:'.TINYIB_DBPATH);
        validateDatabaseSchema();
} catch (PDOException $e) {
    fancyDie('Could not connect to database: '.  $e->getMessage());
}
function validateDatabaseSchema() {
        global $db;
        $db->query('
        CREATE TABLE IF NOT EXISTS '.TINYIB_DBPOSTS.' (
                id INTEGER PRIMARY KEY,
                parent INTEGER NOT NULL,
                timestamp TIMESTAMP NOT NULL,
                bumped TIMESTAMP NOT NULL,
                ip TEXT NOT NULL,
                name TEXT NOT NULL,
                tripcode TEXT NOT NULL,
                email TEXT NOT NULL,
                nameblock TEXT NOT NULL,
                subject TEXT NOT NULL,
                message TEXT NOT NULL,
                password TEXT NOT NULL,
                file TEXT NOT NULL,
                file_hex TEXT NOT NULL,
                file_original TEXT NOT NULL,
                file_size INTEGER NOT NULL DEFAULT "0",
                file_size_formatted TEXT NOT NULL,
                image_width INTEGER NOT NULL DEFAULT "0",
                image_height INTEGER NOT NULL DEFAULT "0",
                thumb TEXT NOT NULL,
                thumb_width INTEGER NOT NULL DEFAULT "0",
                thumb_height INTEGER NOT NULL DEFAULT "0"
        )
        ');
        $db->query('
        CREATE TABLE IF NOT EXISTS '.TINYIB_DBBANS.' (
                id INTEGER PRIMARY KEY,
                ip TEXT NOT NULL,
                timestamp TIMESTAMP NOT NULL,
                expire TIMESTAMP NOT NULL,
                reason TEXT NOT NULL
        )
        ');
        $db->query('
        CREATE TABLE IF NOT EXISTS '.TINYIB_DBLOCKS.' (
                id INTEGER PRIMARY KEY,
                thread INTEGER NOT NULL        
        )
        ');
}
// SQLite PDO Helper
function fetchAndExecute($sql, $parameters=array()) {
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute($parameters);
        $results = $stmt->fetchAll();
        return $results;
}
function uniquePosts() {
        $result = fetchAndExecute(
                'SELECT COUNT(ip) c FROM (SELECT DISTINCT ip FROM '.TINYIB_DBPOSTS.')',
                array()
        );
        return $result[0]['c'];
}
function postByID($id) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' WHERE id=? LIMIT 1',
                array(intval($id))
        );
        if (count($result)) return $result[0];
}
function insertPost($post) {
        $result = fetchAndExecute('
                INSERT INTO '.TINYIB_DBPOSTS.' (
                        parent, timestamp, bumped, ip, name, tripcode, email, nameblock,
                        subject, message, password, file, file_hex, file_original,
                        file_size, file_size_formatted, image_width, image_height,
                        thumb, thumb_width, thumb_height
                ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )',
                array(
                        $post['parent'], time(), time(), $_SERVER['REMOTE_ADDR'],
                        $post['name'], $post['tripcode'], $post['email'], $post['nameblock'],
                        $post['subject'], $post['message'], $post['password'],
                        $post['file'], $post['file_hex'], $post['file_original'],
                        $post['file_size'], $post['file_size_formatted'],
                        $post['image_width'], $post['image_height'], $post['thumb'],
                        $post['thumb_width'], $post['thumb_height']
                )
        );
        return $GLOBALS['db']->lastInsertId();
}
function countPosts() {
        $result = fetchAndExecute(
                'SELECT COUNT(*) c FROM '.TINYIB_DBPOSTS.'',
                array()
        );
        return $result[0]['c'];
}
function latestPosts($count) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' ORDER BY id DESC LIMIT '.intval($count),
                array()
        );
        return $result;
}
function postsByHex($hex) {    
        $result = fetchAndExecute(
                'SELECT id,parent FROM '.TINYIB_DBPOSTS.' WHERE file_hex=? LIMIT 1',
                array($hex)
        );
        return $result;
}
function deletePostByID($id) { 
        $posts = postsInThreadByID($id);
        foreach ($posts as $post) {
                if ($post['id'] != $id) {
                        deletePostImages($post);
                        fetchAndExecute('DELETE FROM '.TINYIB_DBPOSTS.' WHERE id = ?', array($post['id']));
                } else {
                        $thispost = $post;
                }
        }
        if (isset($thispost)) {
                /*if ($thispost['parent'] == 0) {
                        @unlink('res/' . $thispost['id'] . '.html');
                }*/
                deletePostImages($thispost);
                fetchAndExecute('DELETE FROM '.TINYIB_DBPOSTS.' WHERE id = ?', array($thispost['id']));
        }
}
function postsInThreadByID($id) {      
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' WHERE id=? OR parent=? ORDER BY id ASC',
                array($id, $id)
        );
        return $result;
}
function latestRepliesInThreadByID($id) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' WHERE parent = ? ORDER BY id DESC LIMIT '.TINYIB_REPLIESTOSHOW,
                array(intval($id))
        );
        return $result;
}
function lastPostByIP() {      
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' WHERE ip=? ORDER BY id DESC LIMIT 1',
                array($_SERVER['REMOTE_ADDR'])
        );
        if (count($result)) return $result[0];
}
function threadExistsByID($id) {
        $result = fetchAndExecute(
                'SELECT COUNT(id) c FROM '.TINYIB_DBPOSTS.' WHERE id=? AND parent=? LIMIT 1',
                array(intval($id), 0)
        );
        return $result[0]['c'];
}
function bumpThreadByID($id) {
        fetchAndExecute(
                'UPDATE '.TINYIB_DBPOSTS.' SET bumped = ? WHERE id = ?',
                array( time(), intval($id) )
        );
}
function countThreads() {
        $result = fetchAndExecute(
                'SELECT COUNT(id) c FROM '.TINYIB_DBPOSTS .' WHERE parent = ?',
                array(0)
        );
        return $result[0]['c'];
}
function getThreadRange($count, $offset) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBPOSTS.' WHERE parent = ? ORDER BY bumped DESC LIMIT '.intval($offset).','.intval($count),
                array(0)
        );
        return $result;
}
function trimThreads() {
        if (TINYIB_MAXTHREADS > 0) {
                $result = fetchAndExecute(
                        'SELECT id FROM '.TINYIB_DBPOSTS.' WHERE parent = ? ORDER BY bumped DESC LIMIT '.TINYIB_MAXTHREADS.',10',
                        array(0)
                );
                foreach ($result as $post) {
                        deletePostByID($post['id']);
                }
        }
}
function banByID($id) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBBANS.' WHERE id=? LIMIT 1',
                array($id)
        );
        if (count($result)) return $result[0];
}
function banByIP($ip) {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBBANS.' WHERE ip=? LIMIT 1',
                array($ip)
        );
        if (count($result)) return $result[0];
}
function allBans() {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBBANS.' ORDER BY timestamp DESC',
                array()
        );
        return $result;
}
function insertBan($ban) {
        $result = fetchAndExecute(
                'INSERT INTO '.TINYIB_DBBANS.' (ip, timestamp, expire, reason) VALUES (?, ?, ?, ?)',
                array($ban['ip'], time(), $ban['expire'], $ban['reason'])
        );
        return $GLOBALS['db']->lastInsertId();
}
function clearExpiredBans() {
        $result = fetchAndExecute(
                'SELECT * FROM '.TINYIB_DBBANS.' WHERE expire > 0 AND expire <= ?',
                array(time())
        );
        foreach ($result as $ban) deleteBanByID($ban['id']);
}
function deleteBanByID($id) {
        fetchAndExecute('DELETE FROM '.TINYIB_DBBANS.' WHERE id=?', array($id));
}
function isLocked($thread) {
        $result = fetchAndExecute(
                'SELECT COUNT(*) c FROM '.TINYIB_DBLOCKS.' WHERE thread=? LIMIT 1',
                array($thread)
        );
        return $result[0]['c'];
}
function lockThread($thread) {
        if (isLocked($thread)) return;
        fetchAndExecute('INSERT INTO '.TINYIB_DBLOCKS.' (thread) VALUES (?)', array($thread));
}
function unlockThread($thread) {
        if (! isLocked($thread)) return;
        fetchAndExecute('DELETE FROM '.TINYIB_DBLOCKS.' WHERE thread=?', array($thread));
}
function getAllLocks() {
        $result = fetchAndExecute(
                'SELECT thread FROM '.TINYIB_DBLOCKS.';',
                array()
        );
        $ret = array();
        foreach($result as $r) $ret[] = $r['thread'];
        return $ret;
}
// Validate settings
if (TINYIB_TRIPSEED == '' || TINYIB_ADMINPASS == '') {
        fancyDie('Error: TINYIB_TRIPSEED and TINYIB_ADMINPASS must be configured.');
}
foreach (array('db') as $dir) {
        if (!is_writable($dir)) fancyDie("Error: Can't write to directory '$dir'.");
}
if (strlen(TINYIB_TIMEZONE)) date_default_timezone_set(TINYIB_TIMEZONE);
$redirect = true;
list($loggedin, $isadmin) = manageCheckLogIn();
define('LOGGED_IN', $loggedin);
define('IS_ADMIN', $isadmin);
////////////////////////////////////////////////////////////////////////////////
// Controller
if (! isset($_GET['do'])) {
        redirect('?do=page&p=0');
}
switch($_GET['do']) {
        case 'page': {
                if (! isset($_GET['p'])) redirect('?do=page&p=0');
                die( viewPage($_GET['p']) );
        } break;
        case 'thread': {
                if (! isset($_GET['id'])) redirect('?do=page&p=0');
                die( viewThread($_GET['id']));
        } break;
        case 'post': {
                handlePost();
                redirect($redirect);
        } break;
        case 'delpost': {
                handleDeletePost();
                redirect($redirect);
        } break;
        case 'lock': {
                if (! isset($_GET['id']) && $_GET['id'] > 0) redirect('?do=page&p=0');
                $thread_id = intval($_GET['id']);
                if (isLocked($thread_id)) {
                        unlockThread($thread_id);
                } else {
                        lockThread($thread_id);
                }
                redirect('?do=thread&id='.$thread_id);
        } break;
        case 'manage': {
                die( handleManage() );
        } break;
        default: {
                fancyDie('Invalid request.');
        } break;
}
////////////////////////////////////////////////////////////////////////////////
function handleManage() {
        global $redirect;  $redirect = false;
        //global $loggedin;  $loggedin = false;
        //global $isadmin;   $isadmin  = false;
        $text = "";
        //list($loggedin, $isadmin) = manageCheckLogIn();
        if (! isset($_GET['p']) ) {
                redirect('?do=manage&p=home');
        }
        if (! LOGGED_IN) {
                $text .= manageLogInForm();
                die( managePage($text) );
        }
        switch($_GET['p']) {
                case 'bans': {
                        if (! IS_ADMIN) redirect('?do=manage&p=home');
                        clearExpiredBans();
                        if (isset($_POST['ip'])) {
                                if ($_POST['ip'] != '') {
                                        $banexists = banByIP($_POST['ip']);
                                        if ($banexists) {
                                                fancyDie('There is already a ban on record for that IP address.');
                                        }
                                        $ban = array();
                                        $ban['ip'] = $_POST['ip'];
                                        $ban['expire'] = ($_POST['expire'] > 0) ? (time() + $_POST['expire']) : 0;
                                        $ban['reason'] = $_POST['reason'];
                                        insertBan($ban);
                                        $text .= '<b>Successfully added a ban record for ' . $ban['ip'] . '</b><br>';
                                }
                        } elseif (isset($_GET['lift'])) {
                                $ban = banByID($_GET['lift']);
                                if ($ban) {
                                        deleteBanByID($_GET['lift']);
                                        $text .= '<b>Successfully lifted ban on ' . $ban['ip'] . '</b><br>';
                                }
                        }
                        $text .= manageBanForm();
                        $text .= manageBansTable();                            
                } break;
                case 'delete': {
                        $post = postByID($_GET['delete']);
                        if ($post) {
                                deletePostByID($post['id']);
                                $text .= '<b>Post No.' . $post['id'] . ' successfully deleted.</b>';
                        } else {
                                fancyDie("Sorry, there doesn't appear to be a post with that ID.");
                        }
                } break;
                case 'moderate': {
                        if (isset($_GET['moderate']) && $_GET['moderate'] > 0) {
                                $post = postByID($_GET['moderate']);
                                if ($post) {
                                        $text .= manageModeratePost($post);
                                } else {
                                        fancyDie("Sorry, there doesn't appear to be a post with that ID.");
                                }
                        } else {
                                $text .= manageModeratePostForm();
                        }
                } break;
                case 'bump': {
                        if (! isset($_GET['id'])) fancyDie('Invalid request.');
                        bumpThreadByID( intval($_GET['id']) );
                        redirect('?do=manage&p=threads');
                } break;
                case 'logout': {
                        $_SESSION['tinyib'] = '';
                        session_destroy();
                        redirect('?do=manage&p=login');
                } break;
                case 'home': {
                        $text .=
                                'Currently '.countPosts().' posts in '.countThreads().
                                ' threads, made by '.uniquePosts().' users.<br>'.
                                'There are '. count(allBans()).' ban(s).'
                        ;
                } break;
                case 'threads': {
                        $text = manageAllThreads();
                } break;
                default: {
                        fancyDie('Invalid request.');
                } break;
        }
        echo managePage($text);
}
////////////////////////////////////////////////////////////////////////////////
function handleDeletePost() {
        global $redirect;
        if (! isset($_GET['id']) || ! is_numeric($_GET['id'])) {
                fancyDie('No post was selected.');
        }
        $post = postByID($_GET['id']);
        //list($loggedin, $isadmin) = manageCheckLogIn();
        if (LOGGED_IN || (
                (time() - $post['timestamp'] < TINYIB_DELETE_TIMEOUT) &&
                ($post['ip'] == $_SERVER['REMOTE_ADDR'])
        )) {
                if (isset($_GET['force']) && $_GET['force'] == '1') {
                        deletePostByID($post['id']);
                        fancyDie('Post successfully deleted.', 2);
                } else {
                        fancyDie(
                                'Are you sure you want to delete post #'.$post['id']."?\n".
                                (($post['parent'])?'':"Deleting this post will delete the entire thread.\n").
                                'Click <a href="?do=delpost&id='.$post['id'].'&force=1">here</a> to confirm.'
                        );
                }
        } else {
                fancyDie('You have '.TINYIB_DELETE_TIMEOUT.' seconds to delete your own posts.');
        }
        $redirect = false;
}
////////////////////////////////////////////////////////////////////////////////
function handlePost() {
        global $redirect;// global $loggedin; global $isadmin;
        // Validate request
        if (!(isset($_POST["message"]) || isset($_POST["file"]))) {
                fancyDie('Invalid request');
        }
        // Validate user
        if (! LOGGED_IN) {
                checkBanned();
                checkMessageSize();
                checkFlood();
        }
        // Get options
        $modpost = (LOGGED_IN && isset($_POST['modpost']));
        $rawhtml = (LOGGED_IN && isset($_POST['rawhtml']));
        $bump    = (isset($_POST['bump']));    
        // Validate captcha if necessary
        if (TINYIB_USECAPTCHA && ! LOGGED_IN) {
                if (@$_POST['captcha_ex'] != md5(TINYIB_CAPTCHASALT . @$_POST['captcha_out'])) {
                        fancyDie('You appear to have mistyped the verification.');
                }
        }
        $post = newPost();
        $post['parent'] = setParent();
        $post['ip'] = $_SERVER['REMOTE_ADDR'];
        list($post['name'], $post['tripcode']) = nameAndTripcode($_POST["name"]);
        $post['name'] = cleanString(substr($post['name'], 0, 75));
        $post['email'] = ''; // Deprecated
        $post['subject'] = isset($_POST['subject']) ? cleanString(substr($_POST["subject"], 0, 75)) : '';
        $post['password'] = ''; // Deprecated
        // Options
        if ($modpost) {
                $modposttext = IS_ADMIN ? ' <span class="moderator">## Admin</span>' : ' <span class="moderator">## Mod</span>';               
        } else {
                $modposttext = '';             
        }
        if ($rawhtml) {
                $post['message'] = $_POST["message"];
        } else {
                $post['message'] = str_replace("\n", "<br>", colorQuote(postLink(cleanString(rtrim($_POST["message"])))));
        }
        $post['nameblock'] = nameBlock($post['name'], $post['tripcode'], $post['email'], time(), $modposttext);
        // Manage file uploads
        if (isset($_FILES['file'])) {
                if ($_FILES['file']['name'] != "") {
                        validateFileUpload();
                        if (!is_file($_FILES['file']['tmp_name']) || !is_readable($_FILES['file']['tmp_name'])) {
                                fancyDie("File transfer failure. Please retry the submission.");
                        }
                        $post['file_original'] = substr(htmlentities($_FILES['file']['name'], ENT_QUOTES), 0, 50);
                        $post['file_hex'] = md5_file($_FILES['file']['tmp_name']);
                        $post['file_size'] = $_FILES['file']['size'];
                        $post['file_size_formatted'] = convertBytes($post['file_size']);
                        $file_type = strtolower(preg_replace('/.*(\..+)/', '\1', $_FILES['file']['name']));
                        if ($file_type == '.jpeg') { $file_type = '.jpg'; }
                        $file_name = time() . mt_rand(1, 99);
                        $post['thumb'] =  "thumb_" .  $file_name .$file_type;
                        $post['file'] = $file_name . $file_type;
                        $thumb_location = "db/" . $post['thumb'];
                        $file_location = "db/" . $post['file'];
                        if (!($file_type == '.jpg' || $file_type == '.gif' || $file_type == '.png')) {
                                fancyDie("Only GIF, JPG, and PNG files are allowed.");
                        }
                        if (!@getimagesize($_FILES['file']['tmp_name'])) {
                                fancyDie("Failed to read the size of the uploaded file. Please retry the submission.");
                        }
                        $file_info = getimagesize($_FILES['file']['tmp_name']);
                        $file_mime = $file_info['mime'];
                        if (!($file_mime == "image/jpeg" || $file_mime == "image/gif" || $file_mime == "image/png")) {
                                fancyDie("Only GIF, JPG, and PNG files are allowed.");
                        }
                        checkDuplicateImage($post['file_hex']);
                        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_location)) {
                                fancyDie("Could not store uploaded file.");
                        }
                        if ($_FILES['file']['size'] != filesize($file_location)) {
                                fancyDie("File transfer failure. Please go back and try again.");
                        }
                        $post['image_width'] = $file_info[0]; $post['image_height'] = $file_info[1];
                        list($thumb_maxwidth, $thumb_maxheight) = thumbnailDimensions(
                                $post['image_width'], $post['image_height'], $post['parent'] != '0'
                        );
                        if (!createThumbnail($file_location, $thumb_location, $thumb_maxwidth, $thumb_maxheight)) {
                                fancyDie("Could not create thumbnail.");
                        }
                        $thumb_info = getimagesize($thumb_location);
                        $post['thumb_width'] = $thumb_info[0]; $post['thumb_height'] = $thumb_info[1];
                }
        }
if (!CLAIRE_TEXTMODE) {
        if ($post['file'] == '') { // No file uploaded
                if ($post['parent'] == '0') {
                        fancyDie("An image is required to start a thread.");}}}
                if (str_replace('<br>', '', $post['message']) == "") {
                        fancyDie("Please enter a message.");
                }
        $post['id'] = insertPost($post);
        $redirect = '?do=thread&id=' . ($post['parent']=='0' ? $post['id'] : $post['parent']) . '#'. $post['id'];
        trimThreads();
        if ($post['parent'] != '0' && $bump) bumpThreadByID($post['parent']);
}