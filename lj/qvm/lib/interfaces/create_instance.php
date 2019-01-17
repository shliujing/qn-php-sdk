<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luckyboys
 * Date: 2018-12-05
 * Time: 15:32
 */
include_once dirname( __DIR__ ) . '/curl.php';
include_once dirname( __DIR__ ) . '/requester.php';

class CreateInstance implements Requester
{
	/**
	 * 磁盘类型（高效云盘）
	 */
	public const DISK_CATEGORY_CLOUD_EFFICIENCY = 'cloud_efficiency';
	
	/**
	 * 磁盘类型（SSD云盘）
	 */
	public const DISK_CATEGORY_CLOUD_SSD = 'cloud_ssd';
	
	/**
	 * 实例支付类型（包年包月包周）
	 */
	public const INSTANCE_CHARGE_TYPE_PREPAID = 'PrePaid';
	
	/**
	 * 实例支付类型（按需按量）
	 */
	public const INSTANCE_CHARGE_TYPE_POSTPAID = 'PostPaid';
	
	/**
	 * 购买资源的时长单位（按月）
	 */
	public const PERIOD_UNIT_MONTH = 'Month';
	
	/**
	 * 购买资源的时长单位（按周）
	 */
	public const PERIOD_UNIT_WEEK = 'Week';
	
	/**
	 * 区域ID
	 * @var string
	 */
	private $_regionId = '';
	
	/**
	 * 实例所属的可用区编号
	 * @var string
	 */
	private $_zoneId = '';
	
	/**
	 * 镜像ID
	 * @var string
	 */
	private $_imageId = '';
	
	/**
	 * 实例的资源规格
	 * @var string
	 */
	private $_instanceType = '';
	
	/**
	 * 安全组ID
	 * @var string
	 */
	private $_securityGroupId = '';
	
	/**
	 * 实例的名称
	 * @var string
	 */
	private $_instanceName = '';
	
	/**
	 * 云服务器的主机名
	 * @var string
	 */
	private $_hostName = '';
	
	/**
	 * 实例的密码
	 * @var string
	 */
	private $_password = '';
	
	/**
	 * 是否使用镜像预设的密码
	 * @var string
	 */
	private $_passwordInherit = false;
	
	/**
	 * 系统盘
	 * @var array
	 */
	private $_systemDisk;
	
	/**
	 * 数据盘
	 * @var array
	 */
	private $_dataDisk = [];
	
	/**
	 * 虚拟交换机ID
	 * @var string
	 */
	private $_vSwitchId = '';
	
	/**
	 * 虚拟网络ID
	 * @var string
	 */
	private $_vpcId = '';
	
	/**
	 * 实例私网IP地址
	 * @var string
	 */
	private $_privateIpAddress = '';
	
	/**
	 * 实例的付费方式
	 * @var string
	 */
	private $_instanceChargeType = '';
	
	/**
	 * 购买资源的时长
	 * @var int
	 */
	private $_period = 1;
	
	/**
	 * 购买资源的时长单位
	 * @var string
	 */
	private $_periodUnit = self::PERIOD_UNIT_MONTH;
	
	/**
	 * 是否要自动续费
	 * @var bool
	 */
	private $_autoRenew = false;
	
	/**
	 * 每次自动续费的时长
	 * @var bool
	 */
	private $_autoRenewPeriod = 1;
	
	/**
	 * 保证请求幂等性
	 * @var string
	 */
	private $_clientToken = '';
	
	/**
	 * 密钥对名称
	 * @var string
	 */
	private $_keyPairName = '';
	
	/**
	 * 弹性公网IP
	 * @var array
	 */
	private $_eip = [];
	
	private function __construct()
	{
		$this->_systemDisk = [
			'category' => 'cloud_efficiency' ,
			'size' => 40 ,
			'name' => '' ,
			'description' => '' ,
		];
	}
	
	public function getMethod(): string
	{
		return 'POST';
	}
	
	public function getData(): string
	{
		$data = [];
		
		$data['region_id'] = $this->_regionId;
		$data['image_id'] = $this->_imageId;
		$data['instance_type'] = $this->_instanceType;
		$data['security_group_id'] = $this->_securityGroupId;
		$data['vswitch_id'] = $this->_vSwitchId;
		$data['vpc_id'] = $this->_vpcId;
		$data['client_token'] = $this->_clientToken;
		
		if( !empty( $this->_zoneId ) )
		{
			$data['zone_id'] = $this->_zoneId;
		}
		
		if( !empty( $this->_instanceName ) )
		{
			$data['instance_name'] = $this->_instanceName;
		}
		
		if( !empty( $this->_hostName ) )
		{
			$data['host_name'] = $this->_hostName;
		}
		
		if( !empty( $this->_password ) )
		{
			$data['password'] = $this->_password;
		}
		
		if( $this->_passwordInherit )
		{
			$data['password_inherit'] = $this->_passwordInherit;
		}
		
		if( $this->_privateIpAddress )
		{
			$data['private_ip_address'] = $this->_privateIpAddress;
		}
		
		if( $this->_instanceChargeType )
		{
			$data['instance_charge_type'] = $this->_instanceChargeType;
		}
		
		if( $this->_period )
		{
			$data['period'] = $this->_period;
		}
		
		if( $this->_periodUnit )
		{
			$data['period_unit'] = $this->_periodUnit;
		}
		
		if( $this->_autoRenew )
		{
			$data['auto_renew'] = $this->_autoRenew;
			$data['auto_renew_period'] = $this->_autoRenewPeriod;
		}
		
		if( $this->_keyPairName )
		{
			$data['key_pair_name'] = $this->_keyPairName;
		}
		
		if( !empty( $this->_eip ) )
		{
			if( !empty( $this->_eip['id'] ) )
			{
				$data['eip'] = [
					'id' => $this->_eip['id'] ,
				];
			}
			else if( !empty( $this->_eip['bandwidth'] ) )
			{
				$data['eip'] = [
					'bandwidth' => $this->_eip['bandwidth'] ,
					'name' => $this->_eip['name'] ,
				];
			}
		}
		
		$data['system_disk'] = [
			'category' => $this->_systemDisk['category'] ,
			'size' => $this->_systemDisk['size'] ,
			'name' => $this->_systemDisk['name'] ,
			'description' => $this->_systemDisk['description'] ,
		];
		
		if( count( $this->_dataDisk ) > 0 )
		{
			$data['data_disk'] = [];
			
			foreach( $this->_dataDisk as $disk )
			{
				$data['data_disk'][] = [
					'category' => $disk['category'] ,
					'size' => $disk['size'] ,
					'name' => $disk['name'] ,
					'description' => $disk['description'] ,
					'encrypted' => $disk['encrypted'] ,
					'snapshot_id' => $disk['snapshotId'] ,
					'delete_with_instance' => $disk['deleteWithInstance'] ,
				];
			}
		}
		
		return json_encode( $data );
	}
	
	public function getPath(): string
	{
		return '/v1/instance';
	}
	
	public function getQueryData(): array
	{
		return [];
	}
	
	public function getProductCode(): string
	{
		return 'ecs';
	}
	
	public function verify(): bool
	{
		if( empty( $this->_regionId )
			|| empty( $this->_imageId )
			|| empty( $this->_instanceType )
			|| empty( $this->_securityGroupId )
			|| empty( $this->_vSwitchId )
			|| empty( $this->_vpcId )
		)
		{
			return false;
		}
		
		if( !in_array(
			$this->_systemDisk['category'] ,
			[ self::DISK_CATEGORY_CLOUD_EFFICIENCY , self::DISK_CATEGORY_CLOUD_SSD ] ,
			true
		) )
		{
			return false;
		}
		
		if( $this->_systemDisk['size'] < 40 )
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * 设置区域ID
	 * @param string $regionId 区域ID
	 * @return $this
	 */
	public function setRegionId( $regionId ): CreateInstance
	{
		$this->_regionId = $regionId;
		return $this;
	}
	
	/**
	 * 设置实例所属的可用区编号
	 * @param string $zoneId 实例所属的可用区编号
	 * @return $this
	 */
	public function setZoneId( $zoneId ): CreateInstance
	{
		$this->_zoneId = $zoneId;
		return $this;
	}
	
	/**
	 * 设置镜像ID
	 * @param string $imageId 镜像ID
	 * @return $this
	 */
	public function setImageId( $imageId ): CreateInstance
	{
		$this->_imageId = $imageId;
		return $this;
	}
	
	/**
	 * 设置实例的资源规格
	 * @param string $instanceType 实例的资源规格
	 * @return $this
	 */
	public function setInstanceType( $instanceType ): CreateInstance
	{
		$this->_instanceType = $instanceType;
		return $this;
	}
	
	/**
	 * 设置安全组ID
	 * @param string $securityGroupId 安全组ID
	 * @return $this
	 */
	public function setSecurityGroupId( $securityGroupId ): CreateInstance
	{
		$this->_securityGroupId = $securityGroupId;
		return $this;
	}
	
	/**
	 * 设置实例的名称
	 * @param string $instanceName 实例的名称
	 * @return $this
	 */
	public function setInstanceName( $instanceName ): CreateInstance
	{
		$this->_instanceName = $instanceName;
		return $this;
	}
	
	/**
	 * 设置云服务器的主机名
	 * @param string $hostName 云服务器的主机名
	 * @return $this
	 */
	public function setHostName( $hostName ): CreateInstance
	{
		$this->_hostName = $hostName;
		return $this;
	}
	
	/**
	 * 设置实例的密码
	 * @param string $password 实例的密码
	 * @return $this
	 */
	public function setPassword( $password ): CreateInstance
	{
		$this->_password = $password;
		return $this;
	}
	
	/**
	 * 设置是否使用镜像预设的密码
	 * @param bool $passwordInherit 是否使用镜像预设的密码
	 * @return $this
	 */
	public function setPasswordInherit( $passwordInherit ): CreateInstance
	{
		$this->_passwordInherit = $passwordInherit;
		return $this;
	}
	
	/**
	 * 设置系统盘
	 * @param string $category 系统盘的磁盘种类
	 * @param int $size 系统盘大小
	 * @param string $name 系统盘名称
	 * @param string $description 系统盘描述
	 * @return $this
	 */
	public function setSystemDisk( $category , $size = 40 , $name = '' , $description = '' ): CreateInstance
	{
		$this->_systemDisk['category'] = $category;
		$this->_systemDisk['size'] = $size;
		$this->_systemDisk['name'] = $name;
		$this->_systemDisk['description'] = $description;
		return $this;
	}
	
	/**
	 * 设置数据盘
	 * @param string $category 系统盘的磁盘种类
	 * @param int $size 系统盘大小
	 * @param string $name 系统盘名称
	 * @param string $description 系统盘描述
	 * @param bool $encrypted 是否加密
	 * @param string $snapshotId 快照ID
	 * @param bool $deleteWithInstance 是否随实例释放
	 * @return $this
	 */
	public function addDataDisk(
		$category ,
		$size = 40 ,
		$name = '' ,
		$description = '' ,
		$encrypted = false ,
		$snapshotId = '' ,
		$deleteWithInstance = true
	): CreateInstance
	{
		$dataDisk['category'] = $category;
		$dataDisk['size'] = $size;
		$dataDisk['name'] = $name;
		$dataDisk['description'] = $description;
		$dataDisk['encrypted'] = $encrypted;
		$dataDisk['snapshotId'] = $snapshotId;
		$dataDisk['deleteWithInstance'] = $deleteWithInstance;
		
		$this->_dataDisk[] = $dataDisk;
		return $this;
	}
	
	/**
	 * 设置虚拟交换机ID
	 * @param string $vSwitchId 虚拟交换机ID
	 * @return $this
	 */
	public function setVSwitchId( $vSwitchId ): CreateInstance
	{
		$this->_vSwitchId = $vSwitchId;
		return $this;
	}
	
	/**
	 * 设置虚拟网络ID
	 * @param string $vpcId 虚拟网络ID
	 * @return $this
	 */
	public function setVPCId( $vpcId ): CreateInstance
	{
		$this->_vpcId = $vpcId;
		return $this;
	}
	
	/**
	 * 设置实例私网IP地址
	 * @param string $privateIpAddress 实例私网IP地址
	 * @return $this
	 */
	public function setPrivateIpAddress( $privateIpAddress ): CreateInstance
	{
		$this->_privateIpAddress = $privateIpAddress;
		return $this;
	}
	
	/**
	 * 设置实例的付费方式
	 * @param string $instanceChargeType 实例的付费方式
	 * @return $this
	 */
	public function setInstanceChargeType( $instanceChargeType ): CreateInstance
	{
		$this->_instanceChargeType = $instanceChargeType;
		return $this;
	}
	
	/**
	 * 设置购买资源的时长
	 * @param int $period 购买资源的时长
	 * @return $this
	 */
	public function setPeriod( $period ): CreateInstance
	{
		$this->_period = $period;
		return $this;
	}
	
	/**
	 * 设置购买资源的时长单位
	 * @param string $periodUnit 购买资源的时长单位
	 * @return $this
	 */
	public function setPeriodUnit( $periodUnit ): CreateInstance
	{
		$this->_periodUnit = $periodUnit;
		return $this;
	}
	
	/**
	 * 设置是否要自动续费
	 * @param bool $autoRenew 是否要自动续费
	 * @return $this
	 */
	public function setAutoRenew( $autoRenew ): CreateInstance
	{
		$this->_autoRenew = $autoRenew;
		return $this;
	}
	
	/**
	 * 设置每次自动续费的时长
	 * @param int $autoRenewPeriod 每次自动续费的时长
	 * @return $this
	 */
	public function setAutoRenewPeriod( $autoRenewPeriod ): CreateInstance
	{
		$this->_autoRenewPeriod = $autoRenewPeriod;
		return $this;
	}
	
	/**
	 * 设置保证请求幂等性
	 * @param string $clientToken 保证请求幂等性
	 * @return $this
	 */
	public function setClientToken( $clientToken ): CreateInstance
	{
		$this->_clientToken = $clientToken;
		return $this;
	}
	
	/**
	 * 设置密钥对名称
	 * @param string $keyPairName 保证请求幂等性
	 * @return $this
	 */
	public function setKeyPairName( $keyPairName ): CreateInstance
	{
		$this->_keyPairName = $keyPairName;
		return $this;
	}
	
	/**
	 * 绑定已存在弹性公网IP的ID
	 * @param string $eipId 弹性公网IP的ID
	 * @return $this
	 */
	public function bindExistIP( $eipId ): CreateInstance
	{
		$this->_eip = [
			'id' => $eipId ,
		];
		return $this;
	}
	
	/**
	 * 设置分配弹性公网IP
	 * @param int $bandwidth 带宽
	 * @param string $name 名称
	 * @return $this
	 */
	public function setNewEIP( $bandwidth = 1 , $name = '' ): CreateInstance
	{
		$this->_eip = [
			'bandwidth' => $bandwidth ,
			'name' => $name ,
		];
		return $this;
	}
	
	/**
	 * 获取实例
	 * @return CreateInstance
	 */
	public static function getInstance(): CreateInstance
	{
		return new self();
	}
}

