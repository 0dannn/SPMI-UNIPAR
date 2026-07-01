<?php
$u = App\Models\User::where('email', 'auditee@spmi.com')->first();
auth()->login($u);
$req = Illuminate\Http\Request::create('/pengukuran', 'GET');
$res = app()->handle($req);
$content = $res->getContent();
$pos = strpos($content, 'Submit Final ke LPM');
echo substr($content, $pos - 400, 600);
exit;
