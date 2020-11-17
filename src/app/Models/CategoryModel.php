<?php
/******************************************************************************
 * Copyright (C) Omedia.top - All Rights Reserved 2020                        *
 *                                                                            *
 * Unauthorized copying of this file, via any medium is strictly prohibited   *
 * Proprietary and confidential                                               *
 *                                                                            *
 * Written by Omar SAKHRAOUI <webmaster@omedia.top>, 8/2020                   *
 ******************************************************************************/

namespace App\Models;

use App\Models\Entity\CategoryEntity;
use Cake\Database\StatementInterface;
use System\Models\EntityModel;

class CategoryModel extends EntityModel{
	private const TABLE             = self::PREFIX . "category";
	private const TABLE_TOPICS      = self::PREFIX . "topic";
	private const TRANSLATION_TABLE = self::PREFIX . "category_translation";

	public function getAll(): ?array{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "c.*",
			                                     "ct.title",
			                                     "ct.description",
			                                     "topics_count" => $this->queryFactory->newQuery()
			                                                                         ->func()
			                                                                         ->count("t.id"),
		                                     ])
		                            ->from(["c" => self::TABLE])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->leftJoin(["t" => self::TABLE_TOPICS], "t.t_category_id = c.id")
		                            ->group(['c.id'])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()]);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = [];
		foreach($rows as $result){
			$ret[] = new CategoryEntity($result);
		}

		return $ret;
	}

	public function getById(int $id): ?CategoryEntity{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "c.*",
			                                     "ct.title",
			                                     "ct.description",
			                                     "topics_count" => $this->queryFactory->newQuery()
			                                                                         ->func()
			                                                                         ->count("t.id"),
		                                     ])
		                            ->from(["c" => self::TABLE])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->leftJoin(["t" => self::TABLE_TOPICS], "t.t_category_id = c.id")
		                            ->group(['c.id'])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["c.id" => $id])
		                            ->limit(1);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = new CategoryEntity($rows[0]);

		return $ret ?? null;
	}

	public function getBySlug(string $slug): ?CategoryEntity{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "c.*",
			                                     "ct.title",
			                                     "ct.description",
			                                     "topics_count" => $this->queryFactory->newQuery()
			                                                                         ->func()
			                                                                         ->count("t.id"),
		                                     ])
		                            ->from(["c" => self::TABLE])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->leftJoin(["t" => self::TABLE_TOPICS], "t.t_category_id = c.id")
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["c.slug" => $slug])
		                            ->group(['c.id'])
		                            ->limit(1);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = new CategoryEntity($rows[0]);

		return $ret ?? null;
	}

	public function getByType(string $type): ?array{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "c.*",
			                                     "ct.title",
			                                     "ct.description",
			                                     "topics_count" => $this->queryFactory->newQuery()
			                                                                         ->func()
			                                                                         ->count("t.id"),
		                                     ])
		                            ->from(["c" => self::TABLE])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->innerJoin(["ct" => self::TRANSLATION_TABLE], ["c.id = ct.ct_category_id"])
		                            ->leftJoin(["t" => self::TABLE_TOPICS], "t.t_category_id = c.id")
		                            ->group(['c.id'])
		                            ->andWhere(["c.type" => $type]);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = [];
		foreach($rows as $result){
			$ret[] = new CategoryEntity($result);
		}

		return $ret ?? null;
	}
}
