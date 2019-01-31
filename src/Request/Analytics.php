<?php
namespace Logshub\SearchClient\Request;

use Logshub\SearchClient\Response;

class Analytics implements RequestInterface
{
    /**
     * @var string
     */
    protected $serviceId;

    /**
     * @var \DateTime
     */
    protected $dateStart;

    /**
     * @var \DateTime
     */
    protected $dateEnd;

    public function __construct($serviceId, \DateTime $dateStart = null, \DateTime $dateEnd = null)
    {
        $this->serviceId = $serviceId;
        if ($dateStart) {
            $this->dateStart = $dateStart;
        }
        if ($dateEnd) {
            $this->dateEnd = $dateEnd;
        }
    }

    public function isValid()
    {
        return $this->serviceId;
    }

    public function send(\Logshub\SearchClient\Client $client)
    {
        $params = [];
        if ($this->dateStart) {
            // 2014-03-10T05:40:00+01:00
            $params['date_start'] = $this->dateStart->format(\DateTime::RFC3339);
        }
        if ($this->dateEnd) {
            // 2014-03-10T05:40:00+01:00
            $params['date_end'] = $this->dateEnd->format(\DateTime::RFC3339);
        }

        $res = $client->getHttpClient()->request('GET', $client->getUrl() . '/v1/service/'.$this->serviceId.'/analytics?' . http_build_query($params), [
            'headers' => [
                'Authorization' => $client->getAuth(),
            ]
        ]);

        return new Response\Analytics($res);
    }
}
