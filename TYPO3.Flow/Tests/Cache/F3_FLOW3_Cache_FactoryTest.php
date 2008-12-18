<?php
declare(ENCODING = 'utf-8');
namespace F3\FLOW3\Cache;

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
 * @version $Id:\F3\FLOW3\AOP::FLOW3Test.php 201 2007-03-30 11:18:30Z robert $
 */

require_once ('F3_FLOW3_Cache_MockBackend.php');

/**
 * Testcase for the Cache Factory
 *
 * @package FLOW3
 * @subpackage Tests
 * @version $Id:\F3\FLOW3\AOP::FLOW3Test.php 201 2007-03-30 11:18:30Z robert $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class FactoryTest extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function createReturnsInstanceOfTheSpecifiedCacheFrontend() {
		$backend = $this->getMock('F3\FLOW3\Cache\Backend\Null', array(), array(), '', FALSE);
		$cache = $this->getMock('F3\FLOW3\Cache\VariableCache', array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('F3\FLOW3\Object\ManagerInterface');
		$mockObjectManager->expects($this->any())->method('getContext')->will($this->returnValue('Testing'));
		$mockObjectFactory = $this->getMock('F3\FLOW3\Object\FactoryInterface');
		$mockObjectFactory->expects($this->any())->method('create')->will($this->onConsecutiveCalls($backend, $cache));

		$mockCacheManager = $this->getMock('F3\FLOW3\Cache\Manager', array('registerCache'), array(), '', FALSE);
		$factory = new \F3\FLOW3\Cache\Factory($mockObjectManager, $mockObjectFactory, $mockCacheManager);

		$cache = $factory->create('F3_FLOW3_Cache_FactoryTest_Cache', 'F3\FLOW3\Cache\VariableCache', 'F3\FLOW3\Cache\Backend\Null');
		$this->assertType('F3\FLOW3\Cache\VariableCache', $cache);
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function createInjectsAnInstanceOfTheSpecifiedBackendIntoTheCacheFrontend() {
		$backend = $this->getMock('F3\FLOW3\Cache\Backend\File', array(), array(), '', FALSE);
		$cache = $this->getMock('F3\FLOW3\Cache\VariableCache', array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('F3\FLOW3\Object\ManagerInterface');
		$mockObjectManager->expects($this->any())->method('getContext')->will($this->returnValue('Testing'));
		$mockObjectFactory = $this->getMock('F3\FLOW3\Object\FactoryInterface');
		$mockObjectFactory->expects($this->at(0))->method('create')->will($this->returnValue($backend));
		$mockObjectFactory->expects($this->at(1))->method('create')->with('F3\FLOW3\Cache\VariableCache', 'F3_FLOW3_Cache_FactoryTest_Cache', $backend)->will($this->returnValue($cache));

		$mockCacheManager = $this->getMock('F3\FLOW3\Cache\Manager', array('registerCache'), array(), '', FALSE);
		$factory = new \F3\FLOW3\Cache\Factory($mockObjectManager, $mockObjectFactory, $mockCacheManager);

		$factory->create('F3_FLOW3_Cache_FactoryTest_Cache', 'F3\FLOW3\Cache\VariableCache', 'F3\FLOW3\Cache\Backend\File');
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function createPassesBackendOptionsToTheCreatedBackend() {
		$backendOptions = array('someOption' => microtime());

		$backend = $this->getMock('F3\FLOW3\Cache\Backend\File', array(), array(), '', FALSE);
		$cache = $this->getMock('F3\FLOW3\Cache\VariableCache', array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('F3\FLOW3\Object\ManagerInterface');
		$mockObjectManager->expects($this->any())->method('getContext')->will($this->returnValue('Testing'));
		$mockObjectFactory = $this->getMock('F3\FLOW3\Object\FactoryInterface');
		$mockObjectFactory->expects($this->at(0))->method('create')->with('F3\FLOW3\Cache\Backend\Null', 'Testing', $backendOptions)->will($this->returnValue($backend));
		$mockObjectFactory->expects($this->at(1))->method('create')->with('F3\FLOW3\Cache\VariableCache', 'F3_FLOW3_Cache_FactoryTest_Cache', $backend)->will($this->returnValue($cache));

		$mockCacheManager = $this->getMock('F3\FLOW3\Cache\Manager', array('registerCache'), array(), '', FALSE);
		$factory = new \F3\FLOW3\Cache\Factory($mockObjectManager, $mockObjectFactory, $mockCacheManager);

		$cache = $factory->create('F3_FLOW3_Cache_FactoryTest_Cache', 'F3\FLOW3\Cache\VariableCache', 'F3\FLOW3\Cache\Backend\Null', $backendOptions);
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function createdRegistersTheCacheAtTheCacheManager() {
		$backend = $this->getMock('F3\FLOW3\Cache\Backend\Null', array(), array(), '', FALSE);
		$cache = $this->getMock('F3\FLOW3\Cache\VariableCache', array(), array(), '', FALSE);
		$mockObjectManager = $this->getMock('F3\FLOW3\Object\ManagerInterface');
		$mockObjectManager->expects($this->any())->method('getContext')->will($this->returnValue('Testing'));
		$mockObjectFactory = $this->getMock('F3\FLOW3\Object\FactoryInterface');
		$mockObjectFactory->expects($this->any())->method('create')->will($this->onConsecutiveCalls($backend, $cache));

		$mockCacheManager = $this->getMock('F3\FLOW3\Cache\Manager', array('registerCache'), array(), '', FALSE);
		$mockCacheManager->expects($this->once())->method('registerCache')->with($cache);
		$factory = new \F3\FLOW3\Cache\Factory($mockObjectManager, $mockObjectFactory, $mockCacheManager);

		$factory->create('F3_FLOW3_Cache_FactoryTest_Cache', 'F3\FLOW3\Cache\VariableCache', 'F3\FLOW3\Cache\Backend\Null');
	}
}
?>