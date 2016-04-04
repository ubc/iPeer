include epel
include ipeer::web
include ipeer::test
include git

class {'ipeer::db':
    databases => {
        'ipeer' => {
            user => 'ipeer',
            password => 'ipeer',
            host => 'localhost',
            grant => ['ALL'],
        }
    }
}

ipeer::instance { ipeerdev :
    server_domain => "localhost",
    doc_base => "/var/www",
    db_host => $fqdn,
    port => 2000,
    local_config => false,
    import_sample_data => true,
}

# create ipeer_test database for tests
if ! defined(Mysql::Db["ipeer_test"]) {
    @@mysql::db { "ipeer_test":
        user => 'ipeer',
        password => 'ipeer',
        host => 'localhost',
        grant => ['ALL'],
    }
}

cron { sendemails:
    command => "cd /var/www && sh cake/console/cake send_emails",
    user    => root,
    minute  => "*/5"
}
