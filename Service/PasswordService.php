<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use IC\Bundle\Base\ComponentBundle\Exception\ServiceException;

use IC\Bundle\Base\SecurityBundle\Entity\PasswordInterface;
use IC\Bundle\Base\SecurityBundle\Entity\Password;

/**
 * Service responsible for handling password manipulation.
 *
 * @author Juti Noppornpitak <jutin@nationalfibre.net>
 * @author Anthon Pang <anthonp@nationalfibre.net>
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
class PasswordService
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    protected $encoderFactory;

    /**
     * Define the Encoder Factory.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactory $encoderFactory Encoder factory
     */
    public function setEncoderFactory(EncoderFactory $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Parse a credential password.
     *
     * @param \IC\Bundle\Base\SecurityBundle\Entity\PasswordInterface $credential Credential
     * @param string                                                  $salt       Optional salt
     *
     * @return \IC\Bundle\Base\SecurityBundle\Entity\Password
     */
    public function parse(PasswordInterface $credential, $salt = null)
    {
        if ($salt === null) {
            $salt = $this->generateSalt();
        }

        $encoder      = $this->encoderFactory->getEncoder($credential);
        $passwordHash = $encoder->encodePassword($credential->getPlainPassword(), $salt);

        return new Password($passwordHash, $salt);
    }

    /**
     * Generates a salt to be used during password hashing.
     *
     * @return integer
     */
    private function generateSalt()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * Generates random speakable password.
     *
     * @return string
     */
    public function generate()
    {
        // Get words from a source
        $wordList = $this->getWordList(2);

        // Uppercase or not of first letter in first word
        if (rand(0, 1)) {
            $wordList[0] = ucfirst($wordList[0]);
        }

        // Force uppercase of second word
        $wordList[1] = ucfirst($wordList[1]);

        return $this->mergeStringListWithNum(array(
            $this->getNumericString(2),
            $wordList[0],
            $this->getNumericString(2),
            $wordList[1],
            $this->getNumericString(2)
        ));
    }

    /**
     * Merge strings to one with at least one number
     *
     * @param array $stringList
     *
     * @return string
     */
    public function mergeStringListWithNum($stringList)
    {
        $numberExists = false;
        $resultString = '';

        foreach ($stringList as $string) {
            if (is_numeric($string)) {
                $numberExists = true;
            }

            $resultString .= $string;
        }

        if ( ! $numberExists) {
            $resultString .= $this->getNumericString(2, 1);
        }

        return $resultString;
    }

    /**
     * Return list of words for password generation
     *
     * @param integer $numberOfWords
     *
     * @return array
     */
    private function getWordList($numberOfWords)
    {
        $sourcePath = __DIR__ . '/../Resources/data/wordList.php';

        // Get words for password generation
        $wordList = include($sourcePath);
        $keyList  = array_rand($wordList, $numberOfWords);

        $selectedWordList = array_map(
            function ($key) use ($wordList) {
                return $wordList[$key];
            },
            $keyList
        );

        return $selectedWordList;
    }

    /**
     * Return numeric string.
     *
     * @param integer $maxCountOfNumbers
     * @param integer $minCountOfNumbers
     *
     * @return string
     */
    private function getNumericString($maxCountOfNumbers, $minCountOfNumbers = 0)
    {
        $numericString   = '';
        $numberListCount = rand($minCountOfNumbers, $maxCountOfNumbers);

        for ($index = 1; $index <= $numberListCount; $index++) {
            $numericString .= rand(0, 9);
        }

        return $numericString;
    }
}
