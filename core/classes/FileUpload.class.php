<?php
	// define('S3_ACCESS_KEY', 'AKIAIYZTOTU3NS7PEYQA');
	// define('S3_SECRET_KEY', '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');
	// define('DID_DOCS_BUCKET', 'buy-did-docs');
	// define('CREDIT_CARD_BUCKET', 'card-info');
	// define('USER_PROFILE_PIC_BUCKET', 'didx-profile');
	// define('CONTRACT_DOCS_BUCKET', 'userPro');

class FileUpload extends S3{

	private $s3;
	private $account = "Amazon S3";
	private $buckets = [DID_DOCS_BUCKET,CREDIT_CARD_BUCKET,USER_PROFILE_PIC_BUCKET,CONTRACT_DOCS_BUCKET];

	public function __construct()
	{
		$this->s3 = new S3(S3_ACCESS_KEY,S3_SECRET_KEY);
	}


	public function getBuckets(){
		return $this->buckets;
	}


	// @params
	// $file = $_FILES['userfile']['tmp_name'];
	// $name like 700290_sds44555.pdf
	// return true / false
	public function uploadContractDocument($file,$name){
		if ($name == "" || $file == "")
			return false;
		if ($this->s3->putObjectFile($file, CONTRACT_DOCS_BUCKET, $name)){
			return true;
		} else {
			return false;
		}
	}

	// @params
	// $file = $_FILES['userfile']['tmp_name'];
	// $name like 700290_sds44555.pdf
	// return true / false
	public function uploadDidDocument($file,$name){
		if ($name == "" || $file == "")
			return false;
		if ($this->s3->putObjectFile($file, DID_DOCS_BUCKET, $name)){
			return true;
		} else {
			return false;
		}
	}

	// @params
	// $file = $_FILES['userfile']['tmp_name'];
	// $name like 700290_sds44555.pdf
	// return true / false
	public function uploadCreditCardDocument($file,$name){
		if ($name == "" || $file == "")
			return false;
		if ($this->s3->putObjectFile($file, CREDIT_CARD_BUCKET, $name)){
			return true;
		} else {
			return false;
		}
	}

	// @params
	// $file = $_FILES['userfile']['tmp_name'];
	// $name like 700290_sds44555.pdf
	// return true / false
	public function uploadUserProfilePic($file,$name){
		if ($name == "" || $file == "")
			return false;
		if ($this->s3->putObjectFile($file, USER_PROFILE_PIC_BUCKET, $name)){
			return true;
		} else {
			return false;
		}
	}

	public function uploadDocument($file,$name,$bucket){
		if ($name == "" || $file == "" || $bucket == "")
			return false;

		if (!in_array($bucket, $this->buckets)) 
			return false;

		if ($this->s3->putObjectFile($file, $bucket, $name)){
			return true;
		} else {
			return false;
		}
	}

	public function __toString()
    {
        return $this->account;
    } 

    public function getDidDocument($resoursename){
    	return self::getAuthenticatedURL(DID_DOCS_BUCKET,$resoursename, 3600);
    }

}

