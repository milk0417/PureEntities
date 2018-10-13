<?php

declare(strict_types=1);

namespace leinne\pureentities\entity\ai;

use pocketmine\entity\Entity;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;

class EntityAI{

    const JUMP_CANT = 0;
    const JUMP_BLOCK = 1;
    const JUMP_SLAB = 2;

    public static function checkJumpState(Entity $entity, int $x, int $z) : int{
        $block = $entity->getLevel()->getBlock(new Vector3($x, $entity->y, $z));
        if(($aabb = $block->getBoundingBox()) === \null || $block->getSide(Facing::UP, 2)->getBoundingBox() !== \null){
            return EntityAI::JUMP_CANT;
        }

        if(($up = $block->getSide(Facing::UP)->getBoundingBox()) === \null){ //위에 아무 블럭이 없을 때
            if($aabb->maxY - $aabb->minY > 1 || $aabb->maxY === $entity->y){ //울타리 or 반블럭 위
                return EntityAI::JUMP_CANT;
            }else{
                return $aabb->maxY - $entity->y === 0.5 ? EntityAI::JUMP_SLAB : EntityAI::JUMP_BLOCK;
            }
        }elseif($up->maxY - $entity->y === 1.0){ //반블럭 위에서 반블럭+한칸블럭 점프
            return EntityAI::JUMP_BLOCK;
        }
        return EntityAI::JUMP_CANT;
    }

}