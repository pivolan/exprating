<?php
/**
 * Date: 23.03.16
 * Time: 1:42
 */

namespace AppBundle\Tests\Form;


use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\CreateExpertRequest;
use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Image;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Product;
use AppBundle\Entity\RatingSettings;
use AppBundle\Entity\Seo;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryCreateType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\CommentType;
use AppBundle\Form\CreateExpertRequestApproveType;
use AppBundle\Form\CreateExpertRequestType;
use AppBundle\Form\DecisionType;
use AppBundle\Form\ImageType;
use AppBundle\Form\InviteType;
use AppBundle\Form\ModeratorCommentType;
use AppBundle\Form\ProductChangeExpertType;
use AppBundle\Form\ProductChooseCategoryType;
use AppBundle\Form\ProductType;
use AppBundle\Form\RatingSettingsType;
use AppBundle\Form\SeoType;
use AppBundle\Form\UserCompleteType;
use AppBundle\Form\UserEditType;
use AppBundle\Form\UserProfileType;
use AppBundle\Tests\AbstractWebCaseTest;

class InitFormsTest extends AbstractWebCaseTest
{
    /**
     * @dataProvider getForms
     *
     * @param $formName
     * @param $entity
     *
     * @internal     param $data
     */
    public function testForms($formName, $entity)
    {
        $factory = $this->client->getContainer()->get('form.factory');
        $form = $factory->create($formName);
        $this->assertNotNull($form);
        $this->assertNotNull($form->createView());

        $form = $factory->create($formName, $entity);
        $this->assertNotNull($form);
        $this->assertNotNull($form->createView());
    }

    public function getForms()
    {
        return [
            CategoryCreateType::class             => [CategoryCreateType::class, new Category()],
            CategoryType::class                   => [CategoryType::class, new Category()],
            CommentType::class                    => [CommentType::class, new Comment()],
            CreateExpertRequestApproveType::class => [CreateExpertRequestApproveType::class, new CreateExpertRequest()],
            CreateExpertRequestType::class        => [CreateExpertRequestType::class, new CreateExpertRequest()],
            DecisionType::class                   => [DecisionType::class, new CuratorDecision()],
            ImageType::class                      => [ImageType::class, new Image()],
            InviteType::class                     => [InviteType::class, new Invite()],
            ModeratorCommentType::class           => [ModeratorCommentType::class, new Comment()],
            ProductChangeExpertType::class        => [ProductChangeExpertType::class, new Product()],
            ProductChooseCategoryType::class      => [ProductChooseCategoryType::class, new Product()],
            ProductType::class                    => [ProductType::class, new Product()],
            RatingSettingsType::class             => [RatingSettingsType::class, new RatingSettings()],
            SeoType::class                        => [SeoType::class, new Seo()],
            UserCompleteType::class               => [UserCompleteType::class, new User()],
            UserEditType::class                   => [UserEditType::class, new User()],
            UserProfileType::class                => [UserProfileType::class, new User()],
        ];
    }
}