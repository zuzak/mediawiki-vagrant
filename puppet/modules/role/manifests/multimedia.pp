# == Class: role::multimedia
# This class configures MediaWiki for multimedia development:
# - installs ImageMagick
# - raises file size limits
# - configures 404-handler[https://www.mediawiki.org/wiki/Manual:Thumb.php#404_Handler]
# It is meant to contain general configuration of shared use to other
# extensions that are commonly used by the multimedia team in
# development and testing.
class role::multimedia {
    include ::role::thumb_on_404

    # Increase PHP upload size from default puny 2MB
    php::ini { 'uploadsize':
        settings => {
            upload_max_filesize => '100M',
            post_max_size       => '100M',
        }
    }
}
