armasquads-api-restclient
=========================
Master: [![Build Status](https://travis-ci.org/ins0/armasquads-api-restclient.svg?branch=master)](https://travis-ci.org/ins0/armasquads-api-restclient)

php armasquads.com REST API client



example
=========================

    $auth   = new \ArmAsquads\Api\Authentication\HttpHeader('YOUR_API_KEY');
    $api    = new \ArmAsquads\Api($auth);

    Try {

        $squads = $api->getSquads();

        foreach($squads as $squad)
        {
            echo $squad->getName() . "\r\n";
        }

    } Catch(\ArmAsquads\Api\Exception $e)
    {
        echo $e->getMessage() . PHP_EOL;
        echo $api->getLastResponseStatusCode() . PHP_EOL;
        print_r($api->getResponseErrorMessages());
    }