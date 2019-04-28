<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Article;
use PhpTwinfield\DomDocuments\ArticlesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\ArticleMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Articles.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ArticleApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Article based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Article|bool The requested article or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Article
    {
        // Make a request to read a single Article. Set the required values
        $request_article = new Request\Read\Article();
        $request_article
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_article);

        return ArticleMapper::map($response);
    }

    /**
     * Sends an Article instance to Twinfield to update or add.
     *
     * @param Article $article
     * @return Article
     * @throws Exception
     */
    public function send(Article $article): Article
    {
        foreach($this->sendAll([$article]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param Article[] $articles
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $articles): MappedResponseCollection
    {
        Assert::allIsInstanceOf($articles, Article::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($articles) as $chunk) {

            $articlesDocument = new ArticlesDocument();

            foreach ($chunk as $article) {
                $articlesDocument->addArticle($article);
            }

            $responses[] = $this->sendXmlDocument($articlesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "article", function(Response $response): Article {
            return ArticleMapper::map($response);
        });
    }

	/**
     * List all articles.
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    The search field determines which field or fields will be searched. The available fields
     *                         depends on the finder type. Passing a value outside the specified values will cause an
     *                         error.
     * @param int    $firstRow First row to return, useful for paging
     * @param int    $maxRows  Maximum number of rows to return, useful for paging
     * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     *
     * @return Article[] The articles found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = array('ArrayOfString' => array());

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_ITEMS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $articles = [];

        foreach ($response->data->Items->ArrayOfString as $articleArray) {
            $article = new Article();

            if (isset($articleArray->string[0])) {
                $article->setCode($articleArray->string[0]);
                $article->setName($articleArray->string[1]);
            } else {
                $article->setCode($articleArray[0]);
                $article->setName($articleArray[1]);
            }

            $articles[] = $article;
        }

        return $articles;
    }
}