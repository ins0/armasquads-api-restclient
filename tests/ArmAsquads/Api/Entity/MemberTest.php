<?php
require_once('/../AbstractApiTest.php');

class MemberTest extends AbstractApiTest
{
    public function getSampleData()
    {
        $member = new \ArmAsquads\Api\Entity\Member();
        $member->setUuid(1234);
        $member->setUsername('TestUsername');
        $member->setName('TestName');
        $member->setEmail('test@test.com');
        $member->setIcq(12345678);
        $member->setRemark('TestRemark');

        return $member;
    }

    public function testMemberEntity()
    {
        $sample = $this->getSampleData();

        $this->assertSame(1234, $sample->getUuid());
        $this->assertSame('TestUsername', $sample->getUsername());
        $this->assertSame('TestName', $sample->getName());
        $this->assertSame('test@test.com', $sample->getEmail());
        $this->assertSame(12345678, $sample->getIcq());
        $this->assertSame('TestRemark', $sample->getRemark());
    }

    public function testGetMemberFromSquad()
    {
        $sample1 = $this->getSampleData();

        $api = $this->getApi($sample1->getArrayCopy());
        $member = $api->getMember($sample1->getUuid(), 1);

        $this->assertInstanceOf('ArmAsquads\Api\Entity\Member', $member);
        $this->assertSame($sample1->getArrayCopy(), $member->getArrayCopy());
    }

    public function testGetAllMembersFromSquad()
    {
        $sample1 = $this->getSampleData()->getArrayCopy();
        $sample2 = $this->getSampleData()->getArrayCopy();
        $sample3 = $this->getSampleData()->getArrayCopy();

        $api = $this->getApi(array(
            $sample1,
            $sample2,
            $sample3
        ));
        $members = $api->getMembers(1);

        $this->assertCount(3, $members);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Member', $members[0]);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Member', $members[1]);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Member', $members[2]);

        $this->assertSame($sample1, $members[0]->getArrayCopy());
        $this->assertSame($sample2, $members[1]->getArrayCopy());
        $this->assertSame($sample3, $members[2]->getArrayCopy());
    }

    public function testDeleteMember()
    {
        $sampleData = $this->getSampleData();

        $api = $this->getApi(null, 200);
        $api->deleteMember($sampleData->getUuid(), 1);

        $this->assertSame($api->getClient()->getResponseStatusCode(), 200);
    }

    public function testUpdateMember()
    {
        $sampleData = $this->getSampleData();

        $sampleUpdatedData = $this->getSampleData();
        $sampleUpdatedData->setName('OtherNameAsTestDataShouldOverwritten');

        $api = $this->getApi($sampleData->getArrayCopy(), 200);
        $isUpdated = $api->updateMember($sampleUpdatedData, 1);

        $this->assertTrue($isUpdated);
        $this->assertSame($sampleData->getArrayCopy(), $sampleUpdatedData->getArrayCopy());
    }

    public function testCreateMember()
    {
        $sample1 = $this->getSampleData()->getArrayCopy();
        $sampleData = $this->getSampleData();

        $api = $this->getApi($sample1, 201);
        $api->createMember($sampleData, 1);

        $this->assertSame($api->getClient()->getResponseStatusCode(), 201);
        $this->assertInstanceOf('ArmAsquads\Api\Entity\Member', $sampleData);
        $this->assertSame($sample1, $sampleData->getArrayCopy());
    }

}