include ipeer::web
include ipeer::db

ipeer::instance { ipeerdev :
    server_domain => "localhost",
    doc_base => "/var/www",
    db_host => $fqdn
}

file { "/etc/nginx/conf.d/default.conf" :
    ensure => absent,
    notify => Service["nginx"]
}