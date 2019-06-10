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
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Articles.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ArticleApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Article based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Article       The requested Article or Article object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Article
    {
        // Make a request to read a single Article. Set the required values
        $request_article = new Request\Read\Article();
        $request_article
            ->setOffice($office)
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
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_ITEMS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $articleArrayListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(\PhpTwinfield\Article::class, $response->data, $articleArrayListAllTags);
    }

    /**
     * Deletes a specific Article based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Article       The deleted Article or Article object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): Article
    {
        $article = self::get($code, $office);

        if ($article->getResult() == 1) {
            $article->setStatusFromString("deleted");

            try {
                $articleDeleted = self::send($article);
            } catch (ResponseException $e) {
                $articleDeleted = $e->getReturnedObject();
            }

            return $articleDeleted;
        } else {
            return $article;
        }
    }
}