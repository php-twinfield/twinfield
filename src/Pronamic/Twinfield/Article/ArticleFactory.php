<?php
namespace Pronamic\Twinfield\Article;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Article\Mapper\ArticleMapper;
use \Pronamic\Twinfield\Request as Request;

/**
 * ArticleFactory
 * 
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about Articles.
 * 
 * Each function has detailed explanation over what is required, and what
 * happens.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see
 * the advanced guide detailing the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Article
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 * @version 0.0.1
 */
class ArticleFactory extends ParentFactory
{
    /**
     * Requests a specific Article based off the passed in code
     * and office.
     * 
     * Office is an optional parameter.
     * 
     * First it attempts to login with the passed configuration into
     * this instances constructor.  If successful it will get the Service
     * class to handle further interactions.
     * 
     * If no office has been passed it will instead take the default office
     * from the passed in config class.
     * 
     * It makes a new instance of the Request\Read\Article() and sets the
     * office and code parameters.
     * 
     * Using the Service class it will attempt to send the DOM document from
     * Read\Article()
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will return a 
     * \Pronamic\Twinfield\Article\Article instance, made by the
     * \Pronamic\Twinfield\Article\Mapper\ArticleMapper class.
     * 
     * @access public
     * @param int $code
     * @param int $office
     * @return \Pronamic\Twinfield\Article\Article | false
     */
    public function get($code, $office = null)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {

            // Get the secure service class
            $service = $this->getService();

            // No office passed, get the office from the Config
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single Article. Set the required values
            $request_article = new Request\Read\Article();
            $request_article
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_article);
            $this->setResponse($response);

            // Return a mapped article if successful or false if not.
            if ($response->isSuccessful()) {
                return ArticleMapper::map($response);
            } else {
                return false;
            }
        }
    }

    /**
     * Sends a \Pronamic\Twinfield\Article\Article instance to Twinfield
     * to update or add.
     * 
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     * 
     * It will then make an instance of 
     * \Pronamic\Twinfield\Customer\DOM\ArticleDocument where it will
     * pass in the Article class in this methods parameter.
     * 
     * It will then attempt to send the DOM document from ArticlesDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     * 
     * If successful will return true, else will return false.
     * 
     * If you want to map the response back into a Artoc;e use getResponse()->
     * getResponseDocument()->asXML() into the ArticleMapper::map method.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Article\Article $article
     * @return boolean
     */
    public function send(Article $article)
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {

            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of ArticlesDocument and sets the $article
            $articlesDocument = new DOM\ArticlesDocument();
            $articlesDocument->addArticle($article);

            // Send the DOM document request and set the response
            $response = $service->send($articlesDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        }
    }
}
