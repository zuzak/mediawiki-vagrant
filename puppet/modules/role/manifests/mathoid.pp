# == Class: role::mathoid
# This role installs the mathoid service for server side MathJax rendering.
#
class role::mathoid {
    require ::mathoid::install::git
    # use local mathoid renderer
    mediawiki::settings { 'Mathoid':
        values => [
            '$wgMathMathMLUrl = "http://localhost:10042";',
        ],
    }
}
