<?php

namespace App\Repository;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
    /**
     * @param int|null $categoryId
     *
     * @return Job[]
     *
     * @throws \Exception
     */
    public function findActiveJobs(int $categoryId = null)
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC');

        if ($categoryId) {
            $qb->andWhere('j.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return Job|null
     *
     * @throws \Exception
     */
    public function findActiveJob(int $id): ?Job
    {
        return $this->createQueryBuilder('j')
            ->where('j.id = :id')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('id', $id)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Category $category
     *
     * @return AbstractQuery
     *
     * @throws \Exception
     */
    public function getPaginatedActiveJobsByCategoryQuery(Category $category): AbstractQuery
    {
        return $this->createQueryBuilder('j')
            ->where('j.category = :category')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('category', $category)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery();
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function findActiveJobsForAffiliate(Affiliate $affiliate)
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.category', 'c')
            ->leftJoin('c.affiliates', 'a')
            ->where('a.id = :affiliate')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('affiliate', $affiliate)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC')
            ->getQuery()
            ->getResult();

    }
}
