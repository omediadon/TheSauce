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

use App\Models\Entity\TopicEntity;
use Cake\Database\StatementInterface;
use System\Models\EntityModel;

class TopicModel extends EntityModel{
	private const TABLE                      = self::PREFIX . "topic";
	private const CATEGORY_TABLE             = self::PREFIX . "category";
	private const TRANSLATION_TABLE          = self::PREFIX . "topic_translation";
	private const CATEGORY_TRANSLATION_TABLE = self::PREFIX . "category_translation";

	public function getAll(): ?array{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "t.*",
			                                     "tt.title",
			                                     "tt.text",
			                                     "tt.description",
			                                     "category" => "ct.title",
			                                     "c.type",
		                                     ])
		                            ->from(["t" => self::TABLE])
		                            ->innerJoin(["tt" => self::TRANSLATION_TABLE], ["t.id = tt.topic_id"])
		                            ->innerJoin(["c" => self::CATEGORY_TABLE], ["c.id = t.t_category_id"])
		                            ->innerJoin(["ct" => self::CATEGORY_TRANSLATION_TABLE], ["ct.ct_category_id = t.t_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["tt.language_code" => $this->translator->getTheLanguage()]);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = [];
		foreach($rows as $result){
			$ret[] = new TopicEntity($result);
		}

		return $ret;
	}

	/**
	 * SELECT
	 * e.*,
	 * c.type,
	 * ct.title AS category,
	 * tt.title,
	 * tt.description,
	 * tt.text
	 * FROM
	 * `topic_entity` e,
	 * `app_topic` t
	 * CROSS JOIN app_topic_translation tt ON
	 * tt.topic_id = t.id
	 * CROSS JOIN app_category c ON
	 * t.t_category_id = c.id
	 * CROSS JOIN app_category_translation ct ON
	 * ct.ct_category_id = t.t_category_id
	 * WHERE
	 * tt.language_code = "en" AND ct.language_code = "en"
	 */
	public function getById(int $id): ?TopicEntity{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "t.*",
			                                     "c.type",
			                                     "category" => "ct.title",
			                                     "tt.title",
			                                     "tt.description",
			                                     "tt.text",
		                                     ])
		                            ->from([
			                                   "t" => self::TABLE,
		                                   ])
		                            ->innerJoin(["tt" => self::TRANSLATION_TABLE], ["tt.topic_id = t.id"])
		                            ->innerJoin(["c" => self::CATEGORY_TABLE], ["t.t_category_id = c.id"])
		                            ->innerJoin(["ct" => self::CATEGORY_TRANSLATION_TABLE], ["ct.t_category_id = t.t_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["tt.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["t.id" => $id])
		                            ->limit(1);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = new TopicEntity($rows[0]);

		return $ret ?? null;
	}

	public function getBySlug(string $slug): ?TopicEntity{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "t.*",
			                                     "c.type",
			                                     "category" => "ct.title",
			                                     "tt.title",
			                                     "tt.description",
			                                     "tt.text",
		                                     ])
		                            ->from([
			                                   "t" => self::TABLE,
		                                   ])
		                            ->innerJoin(["tt" => self::TRANSLATION_TABLE], ["tt.topic_id = t.id"])
		                            ->innerJoin(["c" => self::CATEGORY_TABLE], ["t.t_category_id = c.id"])
		                            ->innerJoin(["ct" => self::CATEGORY_TRANSLATION_TABLE], ["ct.ct_category_id = t.t_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["tt.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["t.slug" => $slug])
		                            ->limit(1);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = new TopicEntity($rows[0]);

		return $ret ?? null;
	}

	public function getByCategory(string $category): ?array{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "t.*",
			                                     "c.type",
			                                     "category" => "ct.title",
			                                     "tt.title",
			                                     "tt.description",
			                                     "tt.text",
		                                     ])
		                            ->from(["t" => self::TABLE])
		                            ->innerJoin(["tt" => self::TRANSLATION_TABLE], ["tt.topic_id = t.id"])
		                            ->innerJoin(["c" => self::CATEGORY_TABLE], ["t.t_category_id = c.id"])
		                            ->innerJoin(["ct" => self::CATEGORY_TRANSLATION_TABLE], ["ct.ct_category_id = t.t_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["tt.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["t.t_category_id" => $category]);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = [];
		foreach($rows as $result){
			$ret[] = new TopicEntity($result);
		}

		return $ret;
	}

	public function getByType(string $type): ?array{
		$query = $this->queryFactory->newQuery()
		                            ->select([
			                                     "t.*",
			                                     "c.type",
			                                     "category" => "ct.title",
			                                     "tt.title",
			                                     "tt.description",
			                                     "tt.text",
		                                     ])
		                            ->from(["t" => self::TABLE])
		                            ->innerJoin(["tt" => self::TRANSLATION_TABLE], ["tt.topic_id = t.id"])
		                            ->innerJoin(["c" => self::CATEGORY_TABLE], ["t.t_category_id = c.id"])
		                            ->innerJoin(["ct" => self::CATEGORY_TRANSLATION_TABLE], ["ct.ct_category_id = t.t_category_id"])
		                            ->where(["ct.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["tt.language_code" => $this->translator->getTheLanguage()])
		                            ->andWhere(["c.type" => $type]);

		$rows = $query->execute()
		              ->fetchAll(StatementInterface::FETCH_TYPE_ASSOC);
		if(!isset($rows) || is_bool($rows) || count($rows) == 0 || !is_array($rows)){
			return null;
		}
		$ret = [];
		foreach($rows as $result){
			$ret[] = new TopicEntity($result);
		}

		return $ret ?? null;
	}

}
