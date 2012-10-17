class apache {

	package { "apache2":
		ensure => present,
	}

	service { "apache2":
		ensure => running,
		require => Package["apache2"],
		hasstatus => true,
		hasrestart => true,
	}

	define disable_site( $name ) {
		exec { "/usr/sbin/a2dissite $name":
			require => Package["apache2"],
			notify => Exec["force-reload-apache2"],
		}
	}

	define enable_site( $name ) {
		exec { "/usr/sbin/a2ensite $name":
			require => Package["apache2"],
			notify => Exec["force-reload-apache2"],
		}
	}

	exec { "force-reload-apache2":
		command => "/etc/init.d/apache2 force-reload",
		refreshonly => true,
		before => Service["apache2"],
	}


}
