<?php
require_once('/../AbstractApiTest.php');

class SquadsTest extends AbstractApiTest
{
    public function getSampleData()
    {
        $squad = new \ArmAsquads\Api\Entity\Squad();
        $squad->setId(1234);
        $squad->setPrivateID('pRiVaTeKeY');
        $squad->setTag('TestTag');
        $squad->setName('TestName');
        $squad->setEmail('test@test.com');
        $squad->setLogo('logoprivateid');
        $squad->setHomepage('http://www.example.com/');
        $squad->setTitle('TestTile');

        return $squad;
    }

    public function testSquadEntity()
    {
        $sample = $this->getSampleData();

        $this->assertSame(1234, $sample->getId());
        $this->assertSame('pRiVaTeKeY', $sample->getPrivateID());
        $this->assertSame('TestTag', $sample->getTag());
        $this->assertSame('TestName', $sample->getName());
        $this->assertSame('test@test.com', $sample->getEmail());
        $this->assertSame('logoprivateid', $sample->getLogo());
        $this->assertSame('http://www.example.com/', $sample->getHomepage());
        $this->assertSame('TestTile', $sample->getTitle());
        $this->assertSame('http://armasquads.com/user/squads/xml/pRiVaTeKeY/squad.xml', $sample->getSquadXmlUrl());
    }

    public function testGetUserSquads()
    {
        $sample1 = $this->getSampleData()->getArrayCopy();
        $sample2 = $this->getSampleData()->getArrayCopy();
        $sample3 = $this->getSampleData()->getArrayCopy();

        $api = $this->getApi(array(
            $sample1,
            $sample2,
            $sample3
        ));
        $squads = $api->getSquads();

        $this->assertCount(3, $squads);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Squad', $squads[0]);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Squad', $squads[1]);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Squad', $squads[2]);

        $this->assertSame($sample1, $squads[0]->getArrayCopy());
        $this->assertSame($sample2, $squads[1]->getArrayCopy());
        $this->assertSame($sample3, $squads[2]->getArrayCopy());
    }

    public function testGetUserSquadBySquadId()
    {
        $sample1 = $this->getSampleData()->getArrayCopy();
        $api = $this->getApi($sample1);
        $squad = $api->getSquad(1234);

        $this->assertInstanceOf('ArmAsquads\Api\Entity\Squad', $squad);
        $this->assertSame($sample1, $squad->getArrayCopy());
    }

    public function testCreateSquad()
    {
        $sample1 = $this->getSampleData()->getArrayCopy();
        $sampleData = $this->getSampleData();

        $api = $this->getApi($sample1, 201);
        $api->createSquad($sampleData);

        $this->assertInstanceOf('ArmAsquads\Api\Entity\Squad', $sampleData);
        $this->assertSame($sample1, $sampleData->getArrayCopy());
    }

    public function testUpdateSquad()
    {
        $sampleData = $this->getSampleData();

        $sampleUpdatedData = $this->getSampleData();
        $sampleUpdatedData->setName('OtherNameAsTestDataShouldOverwritten');

        $api = $this->getApi($sampleData->getArrayCopy(), 200);
        $isUpdated = $api->updateSquad($sampleUpdatedData);

        $this->assertTrue($isUpdated);
        $this->assertSame($sampleData->getArrayCopy(), $sampleUpdatedData->getArrayCopy());
    }

    public function testDeleteSquad()
    {
        $api = $this->getApi(null, 200);
        $api->deleteSquad(1);

        $this->assertSame($api->getClient()->getResponseStatusCode(), 200);
    }
}