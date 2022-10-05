<?php
namespace Src\model;

class CommentModel {
    private $id;
    private $article_id;
    private $content;
    private $created_date;
    private $account_id; 



    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of article_id
     */ 
    public function getArticle_id()
    {
        return $this->article_id;
    }

    /**
     * Set the value of article_id
     *
     * @return  self
     */ 
    public function setArticle_id($article_id)
    {
        $this->article_id = $article_id;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_date()
    {
        return $this->created_date;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_date($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * Get the value of account_id
     */ 
    public function getAccount_id()
    {
        return $this->account_id;
    }

    /**
     * Set the value of account_id
     *
     * @return  self
     */ 
    public function setAccount_id($account_id)
    {
        $this->account_id = $account_id;

        return $this;
    }
}
