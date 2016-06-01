#!/usr/bin/php
<?php

require_once('../config.php');

$host = get_dbhost($hosts);
$sag = new Sag($host, $dbport);
$myip4 = get_ip(4);
$del = ":";

$types['6721ip'] = array('fam' => '67xx', 'keys'=>'0', 'ekeys' => '0');
$types['6730'] = array('fam' => '67xx', 'keys'=>'8', 'ekeys' => '0');
$types['6731'] = array('fam' => '67xx', 'keys'=>'8', 'ekeys' => '0');
$types['6753'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '0');
$types['6753-1'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '36');
$types['6753-2'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '72');
$types['6755'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '0');
$types['6757'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '0');
$types['6757-1'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '20');
$types['6757-2'] = array('fam' => '67xx', 'keys'=>'6', 'ekeys' => '40');
$types['6835i'] = array('fam' => '68xx', 'keys'=>'12', 'ekeys' => '0');
$types['6837i'] = array('fam' => '68xx', 'keys'=>'12', 'ekeys' => '0');
$types['6865i'] = array('fam' => '68xx', 'keys'=>'8', 'ekeys' => '0');
$types['6867i'] = array('fam' => '68xx', 'keys'=>'6', 'ekeys' => '0');
$types['6867i-1'] = array('fam' => '68xx', 'keys'=>'6', 'ekeys' => '28');
$types['6869i'] = array('fam' => '68xx', 'keys'=>'6', 'ekeys' => '0');
$types['6869i-1'] = array('fam' => '68xx', 'keys'=>'6', 'ekeys' => '28');
$types['6869i-2'] = array('fam' => '68xx', 'keys'=>'6', 'ekeys' => '56');

foreach($types as $mod => $val) {

    $prov['endpoint_brand'] = 'mitel';
    $prov['endpoint_family'] = $val['fam'];
    $prov['endpoint_model'] = $mod;
    /* this is programable keys on phone */
    $prov['usr_keys']['setable_phone_keys'] = $val['keys'];
    $prov['usr_keys']['setable_phone_key_counter'] = '1';
    $prov['usr_keys']['setable_phone_key_value'] = 'fkey';
    /* this is extensions module keys */
    $prov['usr_keys']['setable_module_keys'] = $val['ekeys'];
    $prov['usr_keys']['setable_module_key_counter'] = '1';
    /* there use an special key for extensionsmodule */
    $prov['usr_keys']['setable_module_key_value'] = 'extkey';


// this is example manual plain to jaon format (ACCOUNT MUST BE!)
$in = 
'
sip line{ACCOUNT} auth name: {SIPAUTHNAME}
sip line{ACCOUNT} password: {SIPSECRET}
sip line{ACCOUNT} user name: {SIPUSERNAME}
sip line{ACCOUNT} display name: {SIPCALLERID}
sip line{ACCOUNT} screen name: {INTERNAL} {SIPCALLERID}
sip line{ACCOUNT} proxy ip: {PROXY_SERVER}
sip line{ACCOUNT} proxy port: {PROXY_PORT}
sip line{ACCOUNT} backup proxy ip:
sip line{ACCOUNT} backup proxy port:
sip line{ACCOUNT} registrar ip: {REGISTRAR_SERVER}
sip line{ACCOUNT} registrar port: {PROXY_PORT}
sip line{ACCOUNT} backup registrar ip:
sip line{ACCOUNT} backup registrar port:
sip line{ACCOUNT} vmail: {VMSD}
sip line{ACCOUNT} mode: 0
sip line{ACCOUNT} registration period: {PHONE_REREGISTER}

';

$prov['cfg_account'] = json_decode(plain2json($in, $del)); /* ":" is for split line in 2 pices on : */

// this is example manual plain to jaon format (BASE OPTIONAL)
$in = 
'
# Setup DHCP mode
# dhcp: 1

# Setup HTTP server address
contact rcs: 0
download protocol: HTTP
http server: {PROV_SERVER_URL}
http path: prov/aastra
auto resync mode: 0
lldp: 0

# Startup URI
action uri startup:

# English Language have no file
language 1: lang_de.txt
language 2: lang_fr.txt
language 3: lang_it.txt
language 4: lang_da.txt

priority alerting enabled: 0
xml status scroll delay: 3
missed calls indicator disabled: 0
sip update callerid: 0
sip pai: 1
sip diversion display: 1
show call destination name: 1
time format: 1
date format: 11
use lldp elin: 0
tone set: Europe
# sip nat ip:
sip dial plan: "x+^"
sip dial plan terminator: 1
sip dtmf method: 1
auto resync time: 19:00

';

$prov['cfg_base'] = json_decode(plain2json($in, $del)); /* ":" is for split line in 2 pices on : */


// this is example manual plain to jaon format (BASE OPTIONAL)
$in =  /* putin behavior settings from phone  !!!!!!!*/
'
# Time server
time server disabled: 0
time server1: {HOST_TIMESERVER}
time server2:

language: {LANGUAGE_IDX}
admin password: {PROV_PHONE_PASS}
web language: {LANGUAGE_IDX}
input language: {INPUT_LANG}

sip blf subscription period: {SUBSCRIPT_REREGISTER}
sip blf subscription period: {SUBSCRIPT_REREGISTER}
sip acd subscription period: {SUBSCRIPT_REREGISTER}
sip transaction timer: 600
sip as-feature-event subscription period: {SUBSCRIPT_REREGISTER}

input language: German
language: {LANGUAGE_IDX}
language 1: lang_de.txt
language 3: lang_fr.txt
language 4: lang_it.txt
language 5: lang_da.txt
sip registration period: 60
sip centralized conf: 1000
auto resync time: 19:30
auto resync mode: 1
contrast level: 2
ring tone: 3
time zone name: {TIMEZONE}
directed call pickup: 1
directed call pickup prefix: *8
play a ring splash: 1
sip dial plan: "x+^"
sip silence suppression: 0
https validate certificates: 0
https validate hostname: 0
https validate expires: 0
# Action URI
action uri startup:

';

$prov['cfg_behavior'] = json_decode(plain2json($in, $del));


// this is example manual plain to jaon format (BASE OPTIONAL)
$in =   /* putin behavior settings from phone  !!!!!!!!!*/
'
alert external: 1
alert internal: 2
speaker volume: 1
backlight mode: 2
callers list disabled: 1
bl on time: 16
ringer volume: 3
handset volume: 6
tone set: Germany
xml beep notification: 1

';

$prov['cfg_tone'] = json_decode(plain2json($in, $del));

// this is example manual plain to jaon format (BASE OPTIONAL)
$in =   /* putin behavior settings from phone  !!!!!!!!!!*/
'

prgkey1 type: xml
prgkey1 value: {START_SCRIPT}

topsoftkey1 type: xml
topsoftkey1 label: {NEW_LOGIN}
topsoftkey1 value: {LOGOUT_SCRIPT}

prgkey1 type: xml
prgkey1 value: {LOGOUT_SCRIPT}
prgkey2 type: xml
prgkey2 value: {DND_SCRIPT}
prgkey3 type: xml
prgkey3 value: {CFWD_SCRIPT}
prgkey4 type: xml
prgkey4 value: {PB_SCRIPT}
prgkey5 type: speeddialconf
prgkey5 value: {VMSD}
prgkey6 type: xfer
prgkey6 line: 0
';

$prov['cfg_key'] = json_decode(plain2json($in, $del));

$prov['pvt_counter'] = 1;
$prov['pvt_type'] = 'provisioner';

$prov['pvt_generator'] = 'json2plain';
echo upload_phone_data($prov);
unset($prov);
}

?>