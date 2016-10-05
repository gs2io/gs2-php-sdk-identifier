<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Identifier;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Identifier クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2IdentifierClient extends AbstractGs2Client {

	public static $ENDPOINT = 'identifier';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param array $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * ユーザリストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* userId => ユーザID
	 * 		* ownerId => オーナーID
	 * 		* name => ユーザ名
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeUser($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Identifier', 
					'DescribeUser', 
					Gs2IdentifierClient::$ENDPOINT, 
					'/user',
					$query);
	}
	
	/**
	 * ユーザを作成<br>
	 * <br>
	 * GS2のサービスを利用するにはユーザを作成する必要があります。<br>
	 * ユーザを作成後、ユーザに対して権限設定を行い、ユーザに対応したGSI(クライアントID/シークレット)を発行することでAPIが利用できるようになります。<br>
	 * 
	 * @param array $request
	 * * name => ユーザ名
	 * @return array
	 * * item
	 * 	* userId => ユーザID
	 * 	* ownerId => オーナーID
	 * 	* name => ユーザ名
	 * 	* createAt => 作成日時
	 */
	public function createUser($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		$query = [];
		return $this->doPost(
					'Gs2Identifier', 
					'CreateUser', 
					Gs2IdentifierClient::$ENDPOINT, 
					'/user',
					$body,
					$query);
	}

	/**
	 * ユーザを取得
	 *
	 * @param array $request
	 * * userName => ユーザ名
	 * @return array
	 * * item
	 * 	* userId => ユーザID
	 * 	* ownerId => オーナーID
	 * 	* name => ユーザ名
	 * 	* createAt => 作成日時
	 *
	 */
	public function getUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Identifier',
				'GetUser',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName'],
				$query);
	}

	/**
	 * ユーザを削除
	 * 
	 * @param array $request
	 * * userName => ユーザ名
	 */
	public function deleteUser($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Identifier', 
					'DeleteUser', 
					Gs2IdentifierClient::$ENDPOINT, 
					'/user/'. $request['userName'],
					$query);
	}

	/**
	 * GSIリストを取得
	 * 
	 * @param array $request
	 * * userName => ユーザ名
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* identifierId => GSIID
	 * 		* ownerId => オーナーID
	 * 		* clientId => クライアントID
	 * 		* createAt => 作成日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeIdentifier($request, $pageToken = NULL, $limit = NULL) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
				'Gs2Identifier',
				'DescribeIdentifier',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/identifier',
				$query);
	}
	
	/**
	 * GSIを作成<br>
	 * <br>
	 * GSIはSDKなどでAPIを利用する際に必要となる クライアントID/シークレット です。<br>
	 * AWSでいうIAMのクレデンシャルに相当します。<br>
	 * 
	 * @param array $request
	 * * userName => ユーザ名
	 * @return array
	 * * item
	 * 	* identifierId => GSIID
	 * 	* ownerId => オーナーID
	 * 	* clientId => クライアントID
	 * 	* clientSecret => クライアントシークレット
	 * 	* createAt => 作成日時
	 */
	public function createIdentifier($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		$body = [];
		$query = [];
		return $this->doPost(
				'Gs2Identifier',
				'CreateIdentifier',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/identifier',
				$body,
				$query);
	}
	
	/**
	 * GSIを削除
	 * 
	 * @param array $request
	 * * userName => ユーザ名
	 * * identifierId => GSI ID
	 */
	public function deleteIdentifier($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		if(!array_key_exists('identifierId', $request)) throw new NullPointerException();
		if(is_null($request['identifierId'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
				'Gs2Identifier',
				'DeleteIdentifier',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/identifier/'. $request['identifierId'],
				$query);
	}

	/**
	 * ユーザが保持しているセキュリティポリシー一覧を取得
	 *
	 * @param array $request
	 * * userName => ユーザ名
	 * @return array
	 * * items
	 * 	* array
	 * 		* identifierId => GSIID
	 * 		* ownerId => オーナーID
	 * 		* clientId => クライアントID
	 * 		* createAt => 作成日時
	 */
	public function getHasSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Identifier',
				'HasSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/securityPolicy',
				$query);
	}
	
	/**
	 * ユーザにセキュリティポリシーを割り当てる
	 *
	 * @param array $request
	 * * userName => ユーザ名
	 * * securityPolicyId => セキュリティポリシーID
	 */
	public function attachSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('securityPolicyId', $request)) $body['securityPolicyId'] = $request['securityPolicyId'];
		$query = [];
		return $this->doPut(
				'Gs2Identifier',
				'AttachSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/securityPolicy',
				$body);
	}

	/**
	 * ユーザに割り当てられたセキュリティポリシーを解除
	 *
	 * @param array $request
	 * * userName => ユーザ名
	 * * securityPolicyId => セキュリティポリシーID
	 */
	public function detachSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('userName', $request)) throw new NullPointerException();
		if(is_null($request['userName'])) throw new NullPointerException();
		if(!array_key_exists('securityPolicyId', $request)) throw new NullPointerException();
		if(is_null($request['securityPolicyId'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
				'Gs2Identifier',
				'DetachSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/user/'. $request['userName']. '/securityPolicy/'. $request['securityPolicyId'],
				$query);
	}

	/**
	 * セキュリティポリシーリストを取得
	 *
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* securityPolicyId => セキュリティポリシーID
	 * 		* ownerId => オーナーID
	 * 		* name => セキュリティポリシー名
	 * 		* policy => ポリシー
	 * 		* createAt => 作成日時
	 * 		* updateAt => 更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeSecurityPolicy($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
				'Gs2Identifier',
				'DescribeSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy',
				$query);
	}

	/**
	 * 共用セキュリティポリシーリストを取得
	 *
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* securityPolicyId => セキュリティポリシーID
	 * 		* ownerId => オーナーID
	 * 		* name => セキュリティポリシー名
	 * 		* policy => ポリシー
	 * 		* createAt => 作成日時
	 * 		* updateAt => 更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeCommonSecurityPolicy($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
				'Gs2Identifier',
				'DescribeSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy/common',
				$query);
	}
	
	/**
	 * セキュリティポリシーを作成<br>
	 * <br>
	 * セキュリティポリシーはユーザの権限を定義したものです。<br>
	 * AWSのIAMポリシーに似せて設計されていますが、いくつかAWSのIAMポリシーと比較して劣る点があります。<br>
	 * 2016/9 時点では以下の様な点が IAMポリシー とは異なります。<br>
	 * <ul>
	 * <li>リソースに対するアクセス制御はできません。</li>
	 * <li>アクションのワイルドカードは最後に1箇所のみ利用できます。</li>
	 * </ul>
	 *
	 * @param array $request
	 * * name => セキュリティポリシー名
	 * * policy => ポリシー
	 * @return array
	 * * item
	 * 	* securityPolicyId => セキュリティポリシーID
	 * 	* ownerId => オーナーID
	 * 	* name => セキュリティポリシー名
	 * 	* policy => ポリシー
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function createSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('policy', $request)) $body['policy'] = $request['policy'];
		$query = [];
		return $this->doPost(
				'Gs2Identifier',
				'CreateSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy',
				$body,
				$query);
	}
	
	/**
	 * セキュリティポリシーを取得
	 *
	 * @param array $request
	 * * securityPolicyName => セキュリティポリシー名
	 * @return array
	 * * item
	 * 	* securityPolicyId => セキュリティポリシーID
	 * 	* ownerId => オーナーID
	 * 	* name => セキュリティポリシー名
	 * 	* policy => ポリシー
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 *
	 */
	public function getSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('securityPolicyName', $request)) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Identifier',
				'GetSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy/'. $request['securityPolicyName'],
				$query);
	}
	
	/**
	 * セキュリティポリシーを更新
	 *
	 * @param array $request
	 * * securityPolicyName => セキュリティポリシー名
	 * @return array
	 * * item
	 * 	* securityPolicyId => セキュリティポリシーID
	 * 	* ownerId => オーナーID
	 * 	* name => セキュリティポリシー名
	 * 	* policy => ポリシー
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 */
	public function updateSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('securityPolicyName', $request)) throw new NullPointerException();
		if(is_null($request['securityPolicyName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('policy', $request)) $body['policy'] = $request['policy'];
		$query = [];
		return $this->doPut(
				'Gs2Identifier',
				'UpdateSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy/'. $request['securityPolicyName'],
				$body,
				$query);
	}
	
	/**
	 * セキュリティポリシーを削除
	 *
	 * @param array $request
	 * * securityPolicyName => セキュリティポリシー名
	 */
	public function deleteSecurityPolicy($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('securityPolicyName', $request)) throw new NullPointerException();
		if(is_null($request['securityPolicyName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
				'Gs2Identifier',
				'DeleteSecurityPolicy',
				Gs2IdentifierClient::$ENDPOINT,
				'/securityPolicy/'. $request['securityPolicyName'],
				$query);
	}
	
}