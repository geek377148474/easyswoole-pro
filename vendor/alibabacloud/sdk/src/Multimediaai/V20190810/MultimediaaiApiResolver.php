<?php

namespace AlibabaCloud\Multimediaai\V20190810;

use AlibabaCloud\Client\Resolver\ApiResolver;

/**
 * @method CreateCoverTask createCoverTask(array $options = [])
 * @method CreateFaceGroup createFaceGroup(array $options = [])
 * @method CreateFacePerson createFacePerson(array $options = [])
 * @method CreateGifTask createGifTask(array $options = [])
 * @method CreateLabelTask createLabelTask(array $options = [])
 * @method DeleteFaceGroup deleteFaceGroup(array $options = [])
 * @method DeleteFaceImage deleteFaceImage(array $options = [])
 * @method DeleteFacePerson deleteFacePerson(array $options = [])
 * @method GetTaskResult getTaskResult(array $options = [])
 * @method GetTaskStatus getTaskStatus(array $options = [])
 * @method ListFaceGroups listFaceGroups(array $options = [])
 * @method ListFaceImages listFaceImages(array $options = [])
 * @method ListFacePersons listFacePersons(array $options = [])
 * @method RegisterFaceImage registerFaceImage(array $options = [])
 */
class MultimediaaiApiResolver extends ApiResolver
{
}

class Rpc extends \AlibabaCloud\Client\Resolver\Rpc
{
    /** @var string */
    public $product = 'multimediaai';

    /** @var string */
    public $version = '2019-08-10';

    /** @var string */
    public $method = 'POST';
}

/**
 * @method string getTemplateId()
 * @method $this withTemplateId($value)
 * @method string getVideoUrl()
 * @method $this withVideoUrl($value)
 * @method string getVideoName()
 * @method $this withVideoName($value)
 * @method string getCallbackUrl()
 * @method $this withCallbackUrl($value)
 * @method string getApplicationId()
 * @method $this withApplicationId($value)
 */
class CreateCoverTask extends Rpc
{
}

/**
 * @method string getDescription()
 * @method $this withDescription($value)
 * @method string getFaceGroupName()
 * @method $this withFaceGroupName($value)
 */
class CreateFaceGroup extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getImageUrls()
 * @method $this withImageUrls($value)
 * @method string getFacePersonName()
 * @method $this withFacePersonName($value)
 */
class CreateFacePerson extends Rpc
{
}

/**
 * @method string getTemplateId()
 * @method $this withTemplateId($value)
 * @method string getVideoUrl()
 * @method $this withVideoUrl($value)
 * @method string getVideoName()
 * @method $this withVideoName($value)
 * @method string getCallbackUrl()
 * @method $this withCallbackUrl($value)
 * @method string getApplicationId()
 * @method $this withApplicationId($value)
 */
class CreateGifTask extends Rpc
{
}

/**
 * @method string getTemplateId()
 * @method $this withTemplateId($value)
 * @method string getVideoUrl()
 * @method $this withVideoUrl($value)
 * @method string getVideoName()
 * @method $this withVideoName($value)
 * @method string getCallbackUrl()
 * @method $this withCallbackUrl($value)
 * @method string getApplicationId()
 * @method $this withApplicationId($value)
 */
class CreateLabelTask extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 */
class DeleteFaceGroup extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getFacePersonId()
 * @method $this withFacePersonId($value)
 * @method string getFaceImageId()
 * @method $this withFaceImageId($value)
 */
class DeleteFaceImage extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getFacePersonId()
 * @method $this withFacePersonId($value)
 */
class DeleteFacePerson extends Rpc
{
}

/**
 * @method string getTaskId()
 * @method $this withTaskId($value)
 */
class GetTaskResult extends Rpc
{
}

/**
 * @method string getTaskId()
 * @method $this withTaskId($value)
 */
class GetTaskStatus extends Rpc
{
}

/**
 * @method string getPageNumber()
 * @method $this withPageNumber($value)
 * @method string getPageSize()
 * @method $this withPageSize($value)
 */
class ListFaceGroups extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getFacePersonId()
 * @method $this withFacePersonId($value)
 * @method string getPageNumber()
 * @method $this withPageNumber($value)
 * @method string getPageSize()
 * @method $this withPageSize($value)
 */
class ListFaceImages extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getFacePersonName()
 * @method $this withFacePersonName($value)
 * @method string getPageNumber()
 * @method $this withPageNumber($value)
 * @method string getPageSize()
 * @method $this withPageSize($value)
 */
class ListFacePersons extends Rpc
{
}

/**
 * @method string getFaceGroupId()
 * @method $this withFaceGroupId($value)
 * @method string getFacePersonId()
 * @method $this withFacePersonId($value)
 * @method string getImageUrl()
 * @method $this withImageUrl($value)
 */
class RegisterFaceImage extends Rpc
{
}
