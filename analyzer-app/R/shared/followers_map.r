#map bolsonaro's followers
library(plotly)
library(magrittr)

source("shared/database.r")

getFollowersMap <- function(hashtag1, hashtag2, hashtag3, breaks, breaks_labels, nome_candidato) {
  
  query <- "SELECT latitude, longitude, COUNT(1) AS quantidade, local FROM V_LOCALIZACAO_TWEETS 
                    WHERE tweet LIKE "
  query <- paste(query, "'%#", hashtag1, "%' OR tweet LIKE ", "'%#", hashtag2, "%' OR tweet LIKE ", "'%#", hashtag3, "%' GROUP BY latitude, longitude, local" , sep = '')
  
  
  res <- dbSendQuery(con, query)
  
  data <- fetch(res, n = -1)
  
  dataFrame <- data.frame("longitude" = data$LONGITUDE, "latitude" = data$LATITUDE, "local" = data$LOCAL, "quantidade" = data$quantidade)
  
  dataFrame$q <- with(dataFrame, cut(quantidade, breaks = breaks))
  levels(dataFrame$q) <- paste(breaks_labels, "seguidor(es)")
  dataFrame$q <- as.ordered(dataFrame$q)
  
  geo <- list(
    scope = 'south america',
    showland = TRUE,
    landcolor = toRGB("gray95"),
    countrycolor = toRGB("gray80")
  )
  
  title <- paste('Seguidores de', nome_candidato,'- Brasil - Maio/Junho - 2018')
  
  p <- plot_geo(dataFrame, sizes = c(5, 300), width = 1024, height = 600) %>%
    add_markers(
      x = ~longitude, y = ~latitude, size = ~quantidade, color = ~q,
      text = ~paste(dataFrame$local, " - ", dataFrame$quantidade, 'seguidor(es)')
    )%>%
  layout(title = title, geo = geo)
  return (p)
}