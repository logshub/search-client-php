<?php
namespace Logshub\SearchClient\Response;

class Analytics extends ResponseAbstract
{
    public function getTotalSearches()
    {
        $data = $this->getBodyArray();
        if (empty($data['total_searches'])) {
            return 0;
        }

        return (int)$data['total_searches'];
    }
    
    public function getTotalUsers()
    {
        $data = $this->getBodyArray();
        if (empty($data['total_users'])) {
            return 0;
        }

        return (int)$data['total_users'];
    }
    
    public function getNoResultsRate()
    {
        $data = $this->getBodyArray();
        if (empty($data['no_results_rate'])) {
            return 0.0;
        }

        return (float)$data['no_results_rate'];
    }
    
    public function getTopPhrases()
    {
        return $this->getResultsField('top_phrases');
    }
    
    public function getTopZeroPhrases()
    {
        return $this->getResultsField('top_zero_phrases');
    }
    
    public function getTopCountries()
    {
        return $this->getResultsField('top_countries');
    }
    
    public function getTopCities()
    {
        return $this->getResultsField('top_cities');
    }

    /**
     *
     * @param string $fieldName
     * @return array of \Logshub\SearchClient\Model\AnalyticsResult
     */
    private function getResultsField($fieldName)
    {
        $data = $this->getBodyArray();
        if (empty($data[$fieldName])) {
            return [];
        }

        $items = [];
        foreach ($data[$fieldName] as $item) {
            $items[] = new \Logshub\SearchClient\Model\AnalyticsResult($item['name'], $item['total']);
        }

        return $items;
    }
}
