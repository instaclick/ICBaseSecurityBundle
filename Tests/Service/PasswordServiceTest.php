<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\Service;

use IC\Bundle\Base\TestBundle\Test\TestCase;
use IC\Bundle\Base\SecurityBundle\Service\PasswordService;

/**
 * Service responsible for handling password manipulation.
 *
 * @group ICBaseSecurityBundle
 * @group Service
 * @group Unit
 *
 * @author Mark Kasaboski <markk@nationalfibre.net>
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
class PasswordServiceTest extends TestCase
{
    /**
     * @var \IC\Bundle\Base\SecurityBundle\Service\PasswordService
     */
    private $service;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = new PasswordService();
    }

    /**
     * Test the normal path through parse()
     *
     * @param string $salt The salt
     *
     * @dataProvider provideDataForTestParseNormalPath
     */
    public function testParseNormalPath($salt)
    {
        $this->service->setEncoderFactory($this->createEncoderFactoryMock());

        $result = $this->service->parse($this->createCredentialMock(), $salt);

        $this->assertInstanceOf('IC\Bundle\Base\SecurityBundle\Entity\Password', $result);
    }

    /**
     * Data provider for testParseNormalPath()
     *
     * @return array
     */
    public function provideDataForTestParseNormalPath()
    {
        return array(
            array('saltAndMaybeEvenALittlePepper'),
            array(null),
        );
    }

    /**
     * Test that generate() returns a string that is at least 6 characters
     * in length containing lowercase characters, uppercase characters and integers
     */
    public function testGenerate()
    {
        $this->expectOutputRegex('/^[a-zA-Z0-9]{6,}$/');

        echo $this->service->generate();
    }

    /**
     * Create EncoderFactory mock
     *
     * @return \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private function createEncoderFactoryMock()
    {
        $passwordEncoder = $this->createMock('Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder');

        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with(
                $this->isType('string'),
                $this->isType('string')
            )
            ->will(
                $this->returnValue('youhasasillypassword')
            );

        $encoderFactory  = $this->createMock('Symfony\Component\Security\Core\Encoder\EncoderFactory');

        $encoderFactory
            ->expects($this->once())
            ->method('getEncoder')
            ->with(
                $this->isInstanceOf('IC\Bundle\Base\SecurityBundle\Entity\PasswordInterface')
            )
            ->will(
                $this->returnValue($passwordEncoder)
            );

        return $encoderFactory;
    }

    /**
     * Create Credential mock
     *
     * @return \IC\Bundle\Base\SecurityBundle\Tests\MockObject\Credential
     */
    private function createCredentialMock()
    {
        $entityHelper = $this->getHelper('Unit\Entity');
        $credential   = $entityHelper->createMock('IC\Bundle\Base\SecurityBundle\Tests\MockObject\Entity\Credential', 1);

        $credential->setPlainPassword('p455w0rd');

        return $credential;
    }
}
