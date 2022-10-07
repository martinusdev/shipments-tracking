<?php
declare(strict_types=1);

namespace MartinusDev\ShipmentsTracking\Carriers;

use MartinusDev\ShipmentsTracking\Endpoints\PacketaEndpoint;

class PacketaCarrier extends Carrier
{
    public const NAME = 'Packeta';

    public const REGEX = '/^(Z[0-9]{10})$/i';

    protected $endPointClass = PacketaEndpoint::class;

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @param string $number
     * @return string
     */
    public function getTrackingUrl(string $number): string
    {
        $lang = $this->getLanguage();
        $trackingUrl = 'https://tracking.packeta.com/' . $lang . '/?id=$1';

        return preg_replace(self::REGEX, $trackingUrl, $number);
    }

    private function getLanguage(): string
    {
        $supportedLanguages = [
            'cs',
            'de',
            'hu',
            'sk',
            'pl',
            'ro',
            'uk',
            'es',
            'fr',
            'be',
            'pt',
            'ru',
            'sv',
            'el',
            'it',
            'bg',
            'sl',
            'hr',
            'lv',
            'lt',
            'et',
            'da',
            'fi',
        ];
        foreach ($this->languages as $language) {
            if (in_array($language, $supportedLanguages)) {
                return $language;
            }
        }

        return 'en';
    }
}
