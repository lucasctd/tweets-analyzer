library(wordcloud)
library(tm)
library(plyr)
library(stringi)

source("database.r")

res <- dbSendQuery(con, "SELECT * FROM tweet tw 
                   INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id 
                   WHERE hu.name IN ('#bolsonaro', '#jairbolsonaro', '#bolsonaro2018')")

result <- fetch(res)
attach(result)
value <- sapply(text, function(txt) txt)
corpus <- Corpus(VectorSource(value))

corpus <- tm_map(corpus, content_transformer(tolower))
corpus <- tm_map(corpus, content_transformer(function(x) gsub("https[^[:space:]]*", "", x)))
corpus <- tm_map(corpus, removePunctuation)
corpus <- tm_map(corpus, function(x)removeWords(x,stopwords("pt")))
corpus <- tm_map(corpus, function(x)removeWords(x,c("ter", "olho", "til", "azul", "vai", "pra", "chama", 
                                                    "gorda", "vaqueiro", "diz", "estamos", "pre", "ser", "faz", "nenhuma",
                                                    "163", "sao", "art", "tudo")))
corpus <- tm_map(corpus, content_transformer(function(x) iconv(x, "UTF-8", "latin1")))

wordcloud(corpus, min.freq = 10, max.words = 100, random.order = F)

tdm <- TermDocumentMatrix(corpus)

tdm <- removeSparseTerms(tdm, sparse = 0.97)

df <- as.data.frame(inspect(tdm))

(freq.terms <- findFreqTerms(tdm, lowfreq = 10))

term.freq <- rowSums(as.matrix(tdm))
term.freq <- subset(term.freq, term.freq >= 20)
df <- data.frame(term = names(term.freq), freq = term.freq)

ggplot(df, aes(x=term, y=freq)) + geom_bar(stat="identity") +
  xlab("Terms") + ylab("Count") + coord_flip() +
  theme(axis.text=element_text(size=7))

findAssocs(tdm, "bolsonaro", 0.2)
plot(tdm, term = freq.terms, corThreshold = 0.1, weighting = T)