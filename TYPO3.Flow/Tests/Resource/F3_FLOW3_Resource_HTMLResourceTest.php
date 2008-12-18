<?php
declare(ENCODING = 'utf-8');
namespace F3\FLOW3\Resource;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package FLOW3
 * @subpackage Tests
 * @version $Id:\F3\FLOW3\Object\ClassLoaderTest.php 201 2007-03-30 11:18:30Z robert $
 */

/**
 * Testcase for the HTMLResource
 *
 * @package FLOW3
 * @version $Id:\F3\FLOW3\Object\ClassLoaderTest.php 201 2007-03-30 11:18:30Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class HTMLResourceTest extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function canReturnContent() {
		$HTMLResource = new \F3\FLOW3\Resource\HTMLResource();
		$HTMLResource->setMetadata(array(
			'URI' => 'file://FLOW3/Public/TestTemplate.html',
			'path' => FLOW3_PATH_PACKAGES . 'FLOW3/Resources/Public',
			'name' => 'TestTemplate.html',
			'mediaType' => 'text',
			'mimeType' => 'text/html',
		));

		$this->assertEquals($HTMLResource->getContent(), file_get_contents(FLOW3_PATH_PACKAGES . 'FLOW3/Resources/Public/TestTemplate.html'));
	}
}

?>