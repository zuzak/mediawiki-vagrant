class memcached {

	$memcached_size = '200'
	$memcached_port = '11000'
	$memcached_ip = '0.0.0.0'

	package { memcached:
		ensure => latest;
	}

	file { "/etc/memcached.conf":
		content => template("memcached/memcached.conf.erb"),
		owner => root,
		group => root,
		mode => 0644;
	} ->

	service { memcached:
		require => Package[memcached],
		enable     => true,
		ensure => running;
	}
}
