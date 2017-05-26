<?php
namespace gulltour\rongcloud;

use yii\base\Component;
use yii\base\InvalidConfigException;

require_once __DIR__.'/API/rongcloud.php';

class rongCloud extends Component
{

    public $appKey = '';
    public $secretKey = '';
    private $client;
    public function init()
    {
        parent::init();
        if (!isset($this->appKey)) {
            throw new InvalidConfigException('请先配置appKey');
        }
        if (!isset($this->secretKey)) {
            throw new InvalidConfigException('请先配置secretKey');
        }
        $this->client = new \RongCloud($this->appKey, $this->secretKey);
    }

    /**
     * 获取Token方法
     * @param $userId 用户 Id，最大长度 64 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     * @param $name 用户名称，最大长度 128 字节。用来在 Push 推送时显示用户的名称。（必传）
     * @param $portraitUri 用户头像 URI，最大长度 1024 字节。用来在 Push 推送时显示用户的头像。（必传）
     * @return mixed
     */
    public function getToken($userId, $name, $portraitUri)
    {
       return $this->client->user()->getToken($userId, $name, $portraitUri);
    }

    /**
     * 刷新用户信息方法
     * @param $userId 用户 Id，最大长度 64 字节。是用户在 App 中的唯一标识码，必须保证在同一个 App 内不重复，重复的用户 Id 将被当作是同一用户。（必传）
     * @param $name 用户名称，最大长度 128 字节。用来在 Push 推送时，显示用户的名称，刷新用户名称后 5 分钟内生效。（可选，提供即刷新，不提供忽略）
     * @param $portraitUri 用户头像 URI，最大长度 1024 字节。用来在 Push 推送时显示。（可选，提供即刷新，不提供忽略）
     * @return mixed
     */
    public function refresh($userId, $name, $portraitUri)
    {
        return $this->client->user()->refresh($userId, $name, $portraitUri);
    }

    /**
     * 检查用户在线状态方法
     * @param $userId 用户 Id。（必传）
     * @return mixed
     */
    public function checkOnline($userId)
    {
        return $this->client->user()->checkOnline($userId);
    }

    /**
     * 封禁用户方法（每秒钟限 100 次）
     * @param $userId 用户 Id。（必传）
     * @param $minute 封禁时长,单位为分钟，最大值为43200分钟。（必传）
     * @return mixed
     */
    public function block($userId, $minute)
    {
        return $this->client->user()->block($userId, $minute);
    }

    /**
     * 解除用户封禁方法（每秒钟限 100 次）
     * @param $userId 用户 Id。（必传）
     * @return mixed
     */
    public function unBlock($userId)
    {
        return $this->client->user()->unBlock($userId);
    }

    /**
     * 获取被封禁用户方法（每秒钟限 100 次）
     * @return mixed
     */
    public function queryBlock()
    {
        return $this->client->user()->queryBlock();
    }

    /**
     * 添加用户到黑名单方法（每秒钟限 100 次）
     * @param $userId 用户 Id。（必传）
     * @param $blackUserId 被加黑的用户Id。(必传)
     * @return mixed
     */
    public function addBlacklist($userId, $blackUserId)
    {
        return $this->client->user()->addBlacklist($userId, $blackUserId);
    }

    /**
     * 获取某用户的黑名单列表方法（每秒钟限 100 次）
     * @param $userId 用户 Id。（必传）
     * @return mixed
     */
    public function queryBlacklist($userId)
    {
        return $this->client->user()->queryBlacklist($userId);
    }

    /**
     * 从黑名单中移除用户方法（每秒钟限 100 次）
     * @param $userId 用户 Id。（必传）
     * @param $blackUserId 被移除的用户Id。(必传)
     * @return mixed
     */
    public function removeBlacklist($userId, $blackUserId)
    {
        return $this->client->user()->removeBlacklist($userId, $blackUserId);
    }

    // 发送单聊消息方法（一个用户向另外一个用户发送消息，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人，如：一次发送 1000 人时，示为 1000 条消息。）
    public function publishPrivate()
    {
        return $this->client->message()->publishPrivate('userId1', ["userId2", "userid3", "userId4"], 'RC:VcMsg', "{\"content\":\"hello\",\"extra\":\"helloExtra\",\"duration\":20}", 'thisisapush', '{\"pushData\":\"hello\"}', '4', '0', '0', '0', '0');
    }

    // 发送单聊模板消息方法（一个用户向多个用户发送不同消息内容，单条消息最大 128k。每分钟最多发送 6000 条信息，每次发送用户上限为 1000 人。）
    public function publishTemplate()
    {
        return $this->client->message()->publishTemplate(file_get_contents('TemplateMessage.json'));
    }

    // 发送系统消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM。每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。）
    public function PublishSystem()
    {
        return $this->client->message()->PublishSystem('userId1', ["userId2", "userid3", "userId4"], 'RC:TxtMsg', "{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '0', '0');
    }

    // 发送系统模板消息方法（一个用户向一个或多个用户发送系统消息，单条消息最大 128k，会话类型为 SYSTEM.每秒钟最多发送 100 条消息，每次最多同时向 100 人发送，如：一次发送 100 人时，示为 100 条消息。）
    public function publishSystemTemplate()
    {
        return $this->client->message()->publishSystemTemplate(file_get_contents('TemplateMessage.json'));
    }

    // 发送群组消息方法（以一个用户身份向群组发送消息，单条消息最大 128k.每秒钟最多发送 20 条消息，每次最多向 3 个群组发送，如：一次向 3 个群组发送消息，示为 3 条消息。）
    public function publishGroup()
    {
        return $this->client->message()->publishGroup('userId', ["groupId1", "groupId2", "groupId3"], 'RC:TxtMsg', "{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '1', '1', '0');
    }

    // 发送讨论组消息方法（以一个用户身份向讨论组发送消息，单条消息最大 128k，每秒钟最多发送 20 条消息.）
    public function publishDiscussion()
    {
        return $this->client->message()->publishDiscussion('userId1', 'discussionId1', 'RC:TxtMsg', "{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '1', '1', '0');
    }

    // 发送聊天室消息方法（一个用户向聊天室发送消息，单条消息最大 128k。每秒钟限 100 次。）
    public function publishChatroom()
    {
        return $this->client->message()->publishChatroom('userId1', ["ChatroomId1", "ChatroomId2", "ChatroomId3"], 'RC:TxtMsg', "{\"content\":\"hello\",\"extra\":\"helloExtra\"}");
    }

    // 发送广播消息方法（发送消息给一个应用下的所有注册用户，如用户未在线会对满足条件（绑定手机终端）的用户发送 Push 信息，单条消息最大 128k，会话类型为 SYSTEM。每小时只能发送 1 次，每天最多发送 3 次。）
    public function broadcast()
    {
        return $this->client->message()->broadcast('userId1', 'RC:TxtMsg', "{\"content\":\"哈哈\",\"extra\":\"hello ex\"}", 'thisisapush', '{\"pushData\":\"hello\"}', 'iOS');
    }

    // 消息历史记录下载地址获取 方法消息历史记录下载地址获取方法。获取 APP 内指定某天某小时内的所有会话消息记录的下载地址。（目前支持二人会话、讨论组、群组、聊天室、客服、系统通知消息历史记录下载）
    public function getHistory()
    {
        return $this->client->message()->getHistory('2014010101');
    }

    // 消息历史记录删除方法（删除 APP 内指定某天某小时内的所有会话消息记录。调用该接口返回成功后，date参数指定的某小时的消息记录文件将在随后的5-10分钟内被永久删除。）
    public function deleteMessage()
    {
        return $this->client->message()->deleteMessage('2014010101');
    }

    /***************** wordfilter **************/

    // 添加敏感词方法（设置敏感词后，App 中用户不会收到含有敏感词的消息内容，默认最多设置 50 个敏感词。）
    public function wordfilterAdd()
    {
        return $this->client->wordfilter()->add('money');
    }


    // 查询敏感词列表方法
    public function wordfilterGetList()
    {
        return $this->client->wordfilter()->getList();
    }

    // 移除敏感词方法（从敏感词列表中，移除某一敏感词。）
    public function wordfilterDelete()
    {
        return $this->client->wordfilter()->delete('money');
    }

    /***************** wordfilter **************/

    /**
     * 创建群组方法（创建群组，并将用户加入该群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人，App 内的群组数量没有限制.注：其实本方法是加入群组方法 /group/join 的别名。）
     * @param array $userids 要加入群的用户 Id。（必传）
     * @param $groupId
     * @param $groupName
     * @return mixed
     */
    public function groupCreate($userids = [], $groupId, $groupName)
    {
        return $this->client->group()->create($userids, $groupId, $groupName);
    }

    /**
     * 同步用户所属群组方法（当第一次连接融云服务器时，需要向融云服务器提交 userId 对应的用户当前所加入的所有群组，此接口主要为防止应用中用户群信息同融云已知的用户所属群信息不同步。）
     * @param $userId 被同步群信息的用户 Id。（必传）
     * @param array $groupInfo 该用户的群信息，如群 Id 已经存在，则不会刷新对应群组名称，如果想刷新群组名称请调用 group[id]=name
     * @return mixed
     */
    public function groupSync($userId, $groupInfo = [])
    {
        return $this->client->group()->sync($userId, $groupInfo);
    }

    /**
     * 刷新群组信息方法
     * @param $groupId 群组 Id。（必传）
     * @param $groupName 群组名称。（必传）
     * @return mixed
     */
    public function groupRefresh($groupId, $groupName)
    {
        return $this->client->group()->refresh($groupId, $groupName);
    }

    /**
     * 将用户加入指定群组，用户将可以收到该群的消息，同一用户最多可加入 500 个群，每个群最大至 3000 人。
     * @param array $userIds 要加入群的用户 Id，可提交多个，最多不超过 1000 个。（必传）
     * @param $groupId 要加入的群 Id。（必传）
     * @param $groupName 要加入的群 Id 对应的名称。（必传）
     * @return mixed
     */
    public function groupJoin($userIds = [], $groupId, $groupName)
    {
        return $this->client->group()->join($userIds, $groupId, $groupName);
    }

    /**
     * 查询群成员方法
     * @param $groupId 群 Id。（必传）
     * @return mixed
     */
    public function groupQueryUser($groupId)
    {
        return $this->client->group()->queryUser($groupId);
    }

    /**
     * 退出群组方法（将用户从群中移除，不再接收该群组的消息.）
     * @param $userId 要退出群的用户 Id。（必传）
     * @param $groupId 要退出的群 Id。（必传）
     * @return mixed
     */
    public function groupQuit($userId, $groupId)
    {
        return $this->client->group()->quit($userId, $groupId);
    }

    /**
     * 解散群组方法。（将该群解散，所有用户都无法再接收该群的消息。）
     * @param $userId  操作解散群的用户 Id，可以为任何用户 Id ，非群组创建者也可以解散群组。（必传）
     * @param $groupId 要解散的群 Id。（必传）
     * @return mixed
     */
    public function groupDismiss($userId, $groupId)
    {
        return $this->client->group()->dismiss($userId, $groupId);
    }

    /**
     * 添加禁言群成员方法（在 App 中如果不想让某一用户在群中发言时，可将此用户在群组中禁言，被禁言用户可以接收查看群组中用户聊天信息，但不能发送消息。）
     * @param $userId
     * @param $groupId
     * @param $minute
     * @return mixed
     */
    public function groupAddGagUser($userId, $groupId, $minute)
    {
        return $this->client->group()->addGagUser($userId, $groupId, $minute);
    }

    /**
     * 查询被禁言群成员方法
     * @param $groupId 群组 Id。（必传）
     * @return mixed
     */
    public function groupLisGagUser($groupId)
    {
        return $this->client->group()->lisGagUser($groupId);
    }

    /**
     * 移除禁言群成员方法
     * @param array $userId 用户 Id，支持同时移除多个群成员。（必传）
     * @param $groupId 群组 Id。（必传）
     * @return mixed
     */
    public function groupRollBackGagUser($userId = [], $groupId)
    {
        return $this->client->group()->rollBackGagUser($userId, $groupId);
    }

    /**
     * 创建聊天室方法
     * @param $chatRoomInfo id:要创建的聊天室的id；name:要创建的聊天室的name。（必传）
     */
    public function chatroomCreate($chatRoomInfo)
    {
        return $this->client->chatroom()->create($chatRoomInfo);
    }

    /**
     * 加入聊天室方法
     * @param array $userId 用户 Id，支持同时移除多个群成员。（必传）
     * @param $chatroomId 聊天室的Id （必传）
     * @return mixed
     */
    public function chatroomJoin($userId = [], $chatroomId)
    {
        return $this->client->chatroom()->join($userId, $chatroomId);
    }

    /**
     * 查询聊天室信息方法
     * @param array $chatroomId 聊天室的Id （必传） ["chatroomId1", "chatroomId2", "chatroomId3"]
     * @return mixed
     */
    public function chatroomQuery($chatroomId = [])
    {
        return $this->client->chatroom()->query($chatroomId);
    }

    /**
     * 查询聊天室内用户方法
     * @param $chatroomId 要查询的聊天室 ID（必传）
     * @param $count 要获取的聊天室成员数，上限为 500 ，超过 500 时最多返回 500 个成员（必传）
     * @param $order 加入聊天室的先后顺序， 1 为加入时间正序， 2 为加入时间倒序（必传）
     * @return mixed
     */
    public function chatroomQueryUser($chatroomId, $count, $order)
    {
        return $this->client->chatroom()->queryUser($chatroomId, $count, $order);
    }

    /**
     * 聊天室消息停止分发方法（可实现控制对聊天室中消息是否进行分发，停止分发后聊天室中用户发送的消息，融云服务端不会再将消息发送给聊天室中其他用户。）
     * @param $chatroomId 聊天室 Id。（必传）
     * @return mixed
     */
    public function chatroomStopDistributionMessage($chatroomId)
    {
        return $this->client->chatroom()->stopDistributionMessage($chatroomId);
    }

    /**
     * 聊天室消息恢复分发方法
     * @param $chatroomId 聊天室 Id。（必传）
     * @return mixed
     */
    public function chatroomResumeDistributionMessage($chatroomId)
    {
        return $this->client->chatroom()->resumeDistributionMessage($chatroomId);
    }

    /**
     * 添加禁言聊天室成员方法（在 App 中如果不想让某一用户在聊天室中发言时，可将此用户在聊天室中禁言，被禁言用户可以接收查看聊天室中用户聊天信息，但不能发送消息.）
     * @param $userId 用户 Id。（必传）
     * @param $chatroomId 聊天室 Id。（必传）
     * @param $minute 禁言时长，以分钟为单位，最大值为43200分钟。（必传）
     */
    public function chatroomAddGagUser($userId, $chatroomId, $minute)
    {
        return $this->client->chatroom()->addGagUser($userId, $chatroomId, $minute);
    }

    /**
     * 查询被禁言聊天室成员方法
     * @param $chatroomId 聊天室 Id。（必传）
     * @return mixed
     */
    public function chatroomListGagUser($chatroomId)
    {
        return $this->client->chatroom()->ListGagUser($chatroomId);
    }

    /**
     * 移除禁言聊天室成员方法
     * @param $userId  用户 Id。（必传）
     * @param $chatroomId 聊天室 Id。（必传）
     */
    public function chatroomRollbackGagUser($userId, $chatroomId)
    {
        return $this->client->chatroom()->rollbackGagUser($userId, $chatroomId);
    }


    /**
     * 添加封禁聊天室成员方法
     * @param $userId  用户 Id。（必传）
     * @param $chatroomId 聊天室 Id。（必传）
     * @param $minute 封禁时长，以分钟为单位，最大值为43200分钟。（必传）
     * @return mixed
     */
    public function chatroomAddBlockUser($userId, $chatroomId, $minute)
    {
        return $this->client->chatroom()->addBlockUser($userId, $chatroomId, $minute);
    }

    /**
     * 查询被封禁聊天室成员方法
     * @param $chatroomId 聊天室 Id。（必传）
     * @return mixed
     */
    public function chatroomGetListBlockUser($chatroomId)
    {
        return $this->client->chatroom()->getListBlockUser($chatroomId);
    }

    /**
     * 移除封禁聊天室成员方法
     * @param $userId 用户 Id。（必传）
     * @param $chatroomId 聊天室 Id。（必传）
     * @return mixed
     */
    public function chatroomRollbackBlockUser($userId, $chatroomId)
    {
        return $this->client->chatroom()->rollbackBlockUser($userId, $chatroomId);
    }

    // 添加聊天室消息优先级方法
    //TODO
    public function chatroomAddPriority()
    {
        return $this->client->chatroom()->addPriority(["RC:VcMsg","RC:ImgTextMsg","RC:ImgMsg"]);
    }

    /**
     * 销毁聊天室方法
     * @param array $chatroomId 要销毁的聊天室 Id。（必传） ["chatroomId","chatroomId1","chatroomId2"]
     * @return mixed
     */
    //TODO
    public function chatroomDestroy($chatroomId = [])
    {
        return $this->client->chatroom()->destroy($chatroomId);
    }

    /**
     * 添加聊天室白名单成员方法
     * @param $chatroomId
     * @param array $userId
     * @return mixed
     */
    //TODO
    public function chatroomAddWhiteListUser($chatroomId, $userId = [])
    {
        return $this->client->chatroom()->addWhiteListUser($chatroomId, $userId);
    }

    // 添加 Push 标签方法
    //TODO
    public function chatroomSetUserPushTag()
    {
        return $this->client->push()->setUserPushTag(file_get_contents('UserTag.json'));
    }

    // 广播消息方法（fromuserid 和 message为null即为不落地的push）
    //TODO
    public function chatroomBroadcastPush()
    {
        return $this->client->push()->broadcastPush(file_get_contents('PushMessage.json'));
    }

    /**
     * 获取图片验证码方法
     * @param $appKey
     * @return mixed
     */
    //TODO
    public function chatroomGetImageCode($appKey)
    {
        return $this->client->SMS()->getImageCode($appKey);
    }

    // 发送短信验证码方法。
    //TODO
    public function chatroomSendCode($userId, $chatroomId, $minute)
    {
        return $this->client->SMS()->sendCode('13500000000', 'dsfdsfd', '86', '1408706337', '1408706337');
    }

    // 验证码验证方法
    //TODO
    public function chatroomVerifyCode($userId, $chatroomId, $minute)
    {
        return $this->client->SMS()->verifyCode('2312312', '2312312');
    }


}