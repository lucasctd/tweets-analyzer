library(RMySQL)
library(wordcloud)
library(tm)
library(plyr)
con <- dbConnect(MySQL(), user='homestead', password='secret', dbname='tweets', host='localhost', port = 33060)
res <- dbSendQuery(con, "SELECT * FROM tweet LIMIT 100")
result <- fetch(res)
attach(result)
value <- sapply(text, function(txt) txt)
corpus <- Corpus(VectorSource(value))

f <- content_transformer(function(x) iconv(x, to='latin1', sub='byte'))
corpus <- tm_map(corpus, f)
corpus <- tm_map(corpus, content_transformer(tolower))
corpus <- tm_map(corpus, removePunctuation)
corpus <- tm_map(corpus, function(x)removeWords(x,stopwords("pt")))

wordcloud(corpus, min.freq = 2, max.words = 100, random.order = F, colors = blues9)

tdm <- TermDocumentMatrix(corpus)

tdm <- removeSparseTerms(tdm, sparse = 0.97)

df <- as.data.frame(inspect(tdm))

df.scale <- scale(df)
d <- dist(df.scale, method = "euclidean")

fit.ward2 <- hclust(d, method = "ward.D2")
plot(fit.ward2)
rect.hclust(fit.ward2, k=8)




#for(con in dbListConnections(MySQL())) dbDisconnect(con)
#while(!dbHasCompleted(res)){
#  chunk <- dbFetch(res, n = 5)
#  print(chunk[0])
#}
#corpus <- Corpus(VectorSource(dbFetch(res)))